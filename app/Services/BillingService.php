<?php

namespace App\Services;

use App\Billing;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BillingService
{
    public function list(bool $isPaginated, int $perPage, array $filter)
    {
        try {
            $query = Billing::with(['schoolYear', 'semester', 'billingType', 'studentFee', 'payments']);
            // filters
            // student
            $studentId = $filter['student_id'] ?? false;
            $query->when($studentId, function($q) use ($studentId) {
                return $q->whereHas('student', function($query) use ($studentId) {
                    return $query->where('student_id', $studentId);
                });
            });
            // school year
            $schoolYearId = $filter['school_year_id'] ?? false;
            $query->when($schoolYearId, function($q) use ($schoolYearId) {
                return $q->whereHas('schoolYear', function($query) use ($schoolYearId) {
                    return $query->where('school_year_id', $schoolYearId);
                });
            });
            // semester
            $semesterId = $filter['semester_id'] ?? false;
            $query->when($semesterId, function($q) use ($semesterId) {
                return $q->whereHas('semester', function($query) use ($semesterId) {
                    return $query->where('semester_id', $semesterId);
                });
            });
            // billing type
            $billingTypeId = $filter['billing_type_id'] ?? false;
            $query->when($billingTypeId, function($q) use ($billingTypeId) {
                return $q->whereHas('billingType', function($query) use ($billingTypeId) {
                    return $query->where('billing_type_id', $billingTypeId);
                });
            });
            // // filter by student name
            // $criteria = $request->criteria ?? false;
            // $query->when($criteria, function($q) use ($criteria) {
            //   return $q->whereHas('student', function($query) use ($criteria) {
            //     return $query->where('name', 'like', '%'.$criteria.'%')
            //               ->orWhere('first_name', 'like', '%'.$criteria.'%')
            //               ->orWhere('middle_name', 'like', '%'.$criteria.'%')
            //               ->orWhere('last_name', 'like', '%'.$criteria.'%');
            //   });
            // });
            $billings = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();
            return $billings;
        } catch (Exception $e) {
            Log::info('Error occured during BillingService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
          $billing = Billing::find($id);
          $billing->load(['billingItems', 'billingType', 'student']);
          return $billing;
        } catch (Exception $e) {
            Log::info('Error occured during BillingService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}