<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use App\Billing;
use App\Payment;
use App\Student;
use Carbon\Carbon;
use App\StudentFee;
use App\AcademicRecord;
use App\OrganizationSetting;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\PaymentResource;
use App\SchoolFeeCategory;
use App\Semester;
use App\TranscriptRecord;

class ReportController extends Controller
{
    public function assessmentForm($academicRecordId)
    {
        $data['academicRecord'] = AcademicRecord::find($academicRecordId);
        $data['organization'] = OrganizationSetting::find(1)->load('organizationLogo');
        // $data['academicRecord'] = $academicRecord;
        // return $data['academicRecord']->studentFee;
        // return SchoolFeeCategory::with(['schoolFees' => function ($q) use ($data) {
        //     return $q->with(['studentFeeItems' => function ($query) use ($data) {
        //         return $query->where('student_fee_id', $data['academicRecord']->studentFee->id);
        //     }]);
        // }])->get();
        $data['schoolFeeCategories'] = SchoolFeeCategory::whereHas('schoolFees', function ($q) use ($data) {
            return $q->whereHas('studentFeeItems', function ($query) use ($data) {
                return $query->where('student_fee_id', $data['academicRecord']->studentFee->id);
            });
        })->get();
        $mpdf = new Mpdf();
        $content = view('reports.assessmentform')->with($data);
        $mpdf->WriteHTML($content);
        return $mpdf->Output('', 'S');
        // return $mpdf->Output();
    }

    public function requirementList()
    {
        $mpdf = new Mpdf();
        $data['organization'] = OrganizationSetting::find(1)->load('organizationLogo');
        $content = view('reports.requirementlist')->with($data);
        $mpdf->WriteHTML($content);
        // return $mpdf->Output();
        return $mpdf->Output('', 'S');
    }

    public function statementOfAccount($billingId)
    {
        $mpdf = new Mpdf();
        $data['organization'] = OrganizationSetting::find(1)->load('organizationLogo');
        $data['billing'] = Billing::find($billingId)
            ->load(['term', 'billingItems' => function ($query) {
                return $query->with(['term', 'schoolFee'])
                    ->orderBy('term_id', 'DESC');
            }]);
        $data['student'] = $data['billing']->student()->first();
        $data['academicRecord'] = $data['billing']->studentFee()->first()->academicRecord()
            ->with([
                'level',
                'course',
                'semester',
                'schoolYear',
                'section'
            ])->first();

        $data['terms'] = $data['billing']->studentFee()->first()->terms()->with(['billing' => function ($query) use ($data) {
            return $query->with(['payments'])
                ->where('student_fee_id', $data['billing']->student_fee_id);
        }])->get();
        // return $data['terms'];
        $data['previousBilling'] = Billing::with('payments', 'term')
            ->where('id', '!=', $billingId)
            ->where('student_fee_id', $data['billing']->student_fee_id)
            ->where('billing_type_id', 2)
            ->where('created_at', '<', $data['billing']->created_at)
            ->latest()
            ->first();
        // return $data;
        $content = view('reports.soa')->with($data);
        $mpdf->WriteHTML($content);
        return $mpdf->Output('', 'S');
    }

    public function collectionReport(Request $request)
    {
        $mpdf = new Mpdf(['orientation' => 'L']);
        $data['organization'] = OrganizationSetting::find(1)->load('organizationLogo');

        $query = Payment::with(['paymentMode', 'billing', 'student'])->where('payment_status_id', '=', 2);

        $dateFrom = $request->date_from ?? false;
        $dateTo = $request->date_to ?? false;

        $query->when($dateFrom, function ($q) use ($dateFrom, $dateTo) {
            return $q->whereBetween('date_paid', [$dateFrom, $dateTo]);
        });

        //criteria
        $criteria = $request->criteria ?? false;
        $query->when($criteria, function ($q) use ($criteria) {
            return $q->where(function ($q) use ($criteria) {
                return $q->where('date_paid', 'like', '%' . $criteria . '%')
                    ->orWhere('amount', 'like', '%' . $criteria . '%')
                    ->orWhere('reference_no', 'like', '%' . $criteria . '%')
                    ->orWhereHas('student', function ($query) use ($criteria) {
                        return $query->where(function ($q) use ($criteria) {
                            return $q->where('name', 'like', '%' . $criteria . '%')
                                ->orWhere('first_name', 'like', '%' . $criteria . '%')
                                ->orWhere('middle_name', 'like', '%' . $criteria . '%')
                                ->orWhere('last_name', 'like', '%' . $criteria . '%');
                        });
                    });
            });
        });

        $data['date_from'] = date('m/d/Y', strtotime($dateFrom));
        $data['date_to'] = date('m/d/Y', strtotime($dateTo));
        $data['payments'] = $query->get();

        // return $data;

        $content = view('reports.collections')->with($data);
        $mpdf->WriteHTML($content);
        // return $mpdf->Output();
        return $mpdf->Output('', 'S');
    }

    public function studentLedger($studentId, Request $request)
    {

        $mpdf = new Mpdf();
        $data['organization'] = OrganizationSetting::find(1)->load('organizationLogo');

        $asOfDate = $request->as_of_date ?? Carbon::now()->format('Y-m-d');
        $schoolYearId = $request->school_year_id ?? false;

        $data['student'] = Student::find($studentId);

        DB::statement(DB::raw('set @bal=0;'));

        $billings = Billing::select(
            [
                'billing_no as reference',
                'billing_types.name as txn_type',
                DB::raw('0 as debit'),
                DB::raw('(total_amount) as credit'),
                'billings.created_at as txn_date'
            ]
        )
            ->join('billing_types', 'billings.billing_type_id', '=', 'billing_types.id')
            ->where('student_id', $studentId)
            ->whereDate('billings.created_at', '<', $asOfDate);

        $billings->when($schoolYearId, function ($q) use ($schoolYearId) {
            return $q->where('school_year_id', $schoolYearId);
        });

        $payments = Payment::select(
            [
                'reference_no as reference',
                DB::raw("'Payment' as txn_type"),
                'amount as debit',
                DB::raw('0 as credit'),
                'created_at as txn_date'
            ]
        )->where('student_id', $studentId)
            ->where('payment_status_id', '=', 2) //added filter payment status = approved
            ->whereDate('created_at', '<', $asOfDate);

        $payments->when($schoolYearId, function ($q) use ($schoolYearId) {
            return $q->where('school_year_id', $schoolYearId);
        });

        $billingPayments = $billings->with('billingItems')->union($payments)->orderBy('txn_date');

        $result = DB::table(function ($query) use ($billingPayments) {
            $query->select('*', DB::raw('(@bal := @bal + (credit - debit)) as balance'))
                ->from($billingPayments);
        })->get();

        $data['as_of_date'] = date('m/d/Y', strtotime($asOfDate));
        $data['ledgers'] = $result;


        $content = view('reports.student-ledger')->with($data);
        $mpdf->WriteHTML($content);
        // return $mpdf->Output();
        return $mpdf->Output('', 'S');
    }

    public function registrationForm($academicRecordId)
    {
        $academicRecord = AcademicRecord::find($academicRecordId);
        $data['organization'] = OrganizationSetting::find(1)->load('organizationLogo');
        $data['academicRecord'] = $academicRecord->load([
            'section',
            'schoolYear',
            'level',
            'course',
            'semester',
            'schoolCategory',
            'studentCategory',
            'studentType',
            'application',
            'admission',
            'student' => function ($query) {
                $query->with(['address']);
            },
            'subjects',
        ]);
        $mpdf = new Mpdf();
        $content = view('reports.registrationform')->with($data);
        $mpdf->WriteHTML($content);
        return $mpdf->Output('', 'S');
        // return $mpdf->Output();
    }

    public function transcriptRecord($transcriptRecordId)
    {
        $data['organization'] = OrganizationSetting::find(1)->load('organizationLogo');
        $data['transcriptRecord'] = TranscriptRecord::find($transcriptRecordId);
        $subjects = $data['transcriptRecord']->subjects;
        // return $subjects;
        foreach ($subjects as $subject) {
            $subject->school_year = $this->schoolYear(
                $data['transcriptRecord']->student_id,
                $data['transcriptRecord']->school_category_id,
                $subject['pivot']['level_id'],
                $data['transcriptRecord']->course_id,
                $subject['pivot']['semester_id'],
                $subject['id']
            );
        }
        // $subjects->load('level');
        $subjects->append('level', 'semester', 'school_year');
        // $data['semesters'] = Semester::get();
        // return $data;
        $mpdf = new Mpdf();
        $content = view('reports.transcriptrecord')->with($data);
        $mpdf->WriteHTML($content);
        return $mpdf->Output();
        return $mpdf->Output('', 'S');
    }

    private function schoolYear(int $studentId, int $schoolCategoryId, int $levelId, ?int $courseId, ?int $semesterId, int $subjectId)
    {
        $academicRecords = AcademicRecord::where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->where('level_id', $levelId)
            ->where('school_category_id', $schoolCategoryId)
            ->where('semester_id', $semesterId)
            ->with(['subjects', 'schoolYear'])
            ->get();

        $schoolYears = $academicRecords->map(function ($item) {
            return [
                'subject_ids' => $item->subjects->pluck('id'),
                'school_year' => $item->schoolYear
            ];
        });

        $schoolYear = [];
        foreach ($schoolYears as $value) {
            if ($value['subject_ids']->contains($subjectId)) {
                $schoolYear = $value['school_year'];
            }
        }

        return $schoolYear;
    }
}
