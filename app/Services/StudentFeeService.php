<?php

namespace App\Services;

use App\Student;
use App\StudentFee;
use App\AcademicRecord;
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
            Log::info('Error occured during update getStudentFeeOfAcademicRecord method call: ');
            Log::info($e->getMessage());
        }
    }

    public function getStudentFeeOfAcademicRecord(int $academicRecordId)
    {
        try {
            $studentFee = AcademicRecord::findOrFail($academicRecordId)->studentFee()->with(['studentFeeItems', 'billings' => function($query) {
              $query->first()->first();
            }])->first();
            return $studentFee;
        } catch (Exception $e) {
            Log::info('Error occured during StudentFeeService getStudentFeeOfAcademicRecord method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getStudentFeesOfStudent(int $studentId, bool $isPaginated, int $perPage, array $filters)
    {
        try {
            $query = Student::findOrFail($studentId)->studentFees()
            ->with([
                'studentFeeItems',
                'level',
                'course',
                'semester',
                'schoolYear',
                'student' => function ($query) {
                    return $query->with(['address', 'photo']);
            }]);
            // application status
            $studentFeeStatusId = $filters['student_fee_status_id'] ?? false;
            $query->when($studentFeeStatusId, function($q) use ($studentFeeStatusId) {
                return $q->where('student_fee_status_id', $studentFeeStatusId);
            });

            $studentFees = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();
            return $studentFees;
        } catch (Exception $e) {
            Log::info('Error occured during StudentFeeService getStudentFeesOfStudent method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
