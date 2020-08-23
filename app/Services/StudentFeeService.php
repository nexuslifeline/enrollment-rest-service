<?php

namespace App\Services;

use App\Transcript;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentFeeService
{
    public function getStudentFeeOfTranscript(int $transcriptId)
    {
        try {
            $studentFee = Transcript::findOrFail($transcriptId)->studentFee()->with(['studentFeeItems', 'billings' => function($query) {
              $query->first()->first();
            }])->first();
            return $studentFee;
        } catch (Exception $e) {
            Log::info('Error occured during StudentFeeService getStudentFeeOfTranscript method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}