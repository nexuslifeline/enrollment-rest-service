<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use App\Payment;
use App\StudentFee;
use App\AcademicRecord;
use App\OrganizationSetting;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\PaymentResource;


class ReportController extends Controller
{
    public function assessmentForm($academicRecordId)
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
            'student' => function($query) {
                $query->with(['address']);
            },
            'subjects',
            'studentFee' => function ($q) {
                return $q->with(['studentFeeItems']);
            }
        ]);
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

    public function statementOfAccount($studentFeeId)
    {
        $mpdf = new Mpdf();
        $data['organization'] = OrganizationSetting::find(1)->load('organizationLogo');
        $data['studentFee'] = StudentFee::find($studentFeeId)
            ->load([
                'student',
                'academicRecord' => function ($q) {
                    return $q->with([
                        'level',
                        'course',
                        'semester',
                        'schoolYear',
                        'section'
                    ]);
                }
            ]);
        $content = view('reports.soa')->with($data);
        $mpdf->WriteHTML($content);
        // return $mpdf->Output();
        return $mpdf->Output('', 'S');
    }

    public function collectionReport(Request $request)
    {
        $mpdf = new Mpdf(['orientation' => 'L']);
        $data['organization'] = OrganizationSetting::find(1)->load('organizationLogo');

        $query = Payment::with(['paymentMode', 'billing', 'student'])->where('payment_status_id', '=', 2);

        $dateFrom = $request->date_from ?? false;
        $dateTo = $request->date_to ?? false;

        $query->when($dateFrom, function($q) use ($dateFrom, $dateTo) {
            return $q->whereBetween('date_paid', [$dateFrom, $dateTo]);
        });

         //criteria
         $criteria = $request->criteria ?? false;
         $query->when($criteria, function($q) use ($criteria) {
           return $q->where(function($q) use ($criteria) {
               return $q->where('date_paid', 'like', '%'.$criteria.'%')
               ->orWhere('amount', 'like', '%'.$criteria.'%')
               ->orWhere('reference_no', 'like', '%'.$criteria.'%')
               ->orWhereHas('student', function($query) use ($criteria) {
                   return $query->where(function($q) use ($criteria) {
                       return $q->where('name', 'like', '%'.$criteria.'%')
                       ->orWhere('first_name', 'like', '%'.$criteria.'%')
                       ->orWhere('middle_name', 'like', '%'.$criteria.'%')
                       ->orWhere('last_name', 'like', '%'.$criteria.'%');
                   });
               });
           });
         });

        $data['payments'] = $query->get();

        // return $data;

        $content = view('reports.collections')->with($data);
        $mpdf->WriteHTML($content);
        // return $mpdf->Output();
        return $mpdf->Output('', 'S');
    }
}