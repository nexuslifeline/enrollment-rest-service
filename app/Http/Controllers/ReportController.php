<?php

namespace App\Http\Controllers;

use App\AcademicRecord;
use Mpdf\Mpdf;

class ReportController extends Controller
{
    public function assessmentForm($academicRecordId)
    {
        $academicRecord = AcademicRecord::find($academicRecordId);
        $data['subjects'] = $academicRecord->subjects()->get();
        $data['student_fee'] = $academicRecord->studentFee()->first();
        $data['fees'] = $data['student_fee']->studentFeeItems()->with('schoolFeeCategory')->get();
        // $data['categories'] = $data['student_fee']->studentFeeItems()->with('schoolFeeCategory')->get();
        // return $data['categories'];
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
            }
        ]);

        $mpdf = new Mpdf();
        $content = view('reports.assessmentform')->with($data);
        $mpdf->WriteHTML($content);
        return $mpdf->Output('', 'S');
        // $mpdf->Output();
    }

    public function requirementList()
    {
        $mpdf = new Mpdf();
        $content = view('reports.requirementlist');
        $mpdf->WriteHTML($content);
        return $mpdf->Output('', 'S');
    }
}