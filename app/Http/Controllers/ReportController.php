<?php

namespace App\Http\Controllers;

use App\Transcript;
use Mpdf\Mpdf;

class ReportController extends Controller
{
    public function assessmentForm($transcriptId)
    {
        $transcript = Transcript::find($transcriptId);
        $data['subjects'] = $transcript->subjects()->get();
        $data['student_fee'] = $transcript->studentFee()->first();
        $data['fees'] = $data['student_fee']->studentFeeItems()->get();
        $data['transcript'] = $transcript->load([
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
        $mpdf->Output();
    }
}