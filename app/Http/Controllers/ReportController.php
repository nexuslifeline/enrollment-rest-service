<?php

namespace App\Http\Controllers;

use App\AcademicRecord;
use App\OrganizationSetting;
use App\StudentFee;
use Mpdf\Mpdf;

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
}