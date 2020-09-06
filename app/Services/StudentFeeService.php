<?php

namespace App\Services;

use App\Student;
use App\StudentFee;
use App\Transcript;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentFeeService
{
    public function update(int $id, array $data, array $studentFeeItems)
    {
        try {
            $studentFee = StudentFee::find($id);
            $studentFee->update($data);
            $fees = $studentFeeItems;
            $items = [];
            foreach ($fees as $fee) {
                $items[$fee['school_fee_id']] = [
                    'amount' => $fee['amount'],
                    'notes' => $fee['notes']
                ];
            }
            $studentFee->studentFeeItems()->sync($items);
        } catch (Exception $e) {
            Log::info('Error occured during update getStudentFeeOfTranscript method call: ');
            Log::info($e->getMessage());
        }
    }

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
