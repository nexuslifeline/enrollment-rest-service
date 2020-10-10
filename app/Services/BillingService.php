<?php

namespace App\Services;

use App\Billing;
use App\Term;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BillingService
{
    public function list(bool $isPaginated, int $perPage, array $filters)
    {
        try {
            $query = Billing::with(['schoolYear', 'semester', 'billingType', 'studentFee', 'payments',
            'student' => function ($query) {
                return $query->with(['address', 'photo']);
            }]);
            // filters
            // student
            $studentId = $filters['student_id'] ?? false;
            $query->when($studentId, function ($q) use ($studentId) {
                return $q->whereHas('student', function ($query) use ($studentId) {
                    return $query->where('student_id', $studentId);
                });
            });
            // school year
            $schoolYearId = $filters['school_year_id'] ?? false;
            $query->when($schoolYearId, function ($q) use ($schoolYearId) {
                return $q->whereHas('schoolYear', function ($query) use ($schoolYearId) {
                    return $query->where('school_year_id', $schoolYearId);
                });
            });
            // semester
            $semesterId = $filters['semester_id'] ?? false;
            $query->when($semesterId, function ($q) use ($semesterId) {
                return $q->whereHas('semester', function ($query) use ($semesterId) {
                    return $query->where('semester_id', $semesterId);
                });
            });
            // school category
            $schoolCategoryId = $filters['school_category_id'] ?? false;
            $query->when($schoolCategoryId, function ($q) use ($schoolCategoryId) {
                return $q->whereHas('studentFee', function ($query) use ($schoolCategoryId) {
                    return $query->whereHas('academicRecord', function ($q) use ($schoolCategoryId) {
                        return $q->where('school_category_id', $schoolCategoryId);
                    });
                });
            });
            // billing status
            $billingStatusId = $filters['billing_status_id'] ?? false;
            $query->when($billingStatusId, function ($q) use ($billingStatusId) {
                return $q->where('billing_status_id', $billingStatusId);
            });
            // billing type
            $billingTypeId = $filters['billing_type_id'] ?? false;
            $query->when($billingTypeId, function ($q) use ($billingTypeId) {
                return $q->where('billing_type_id', $billingTypeId);
            });
            // filter by student name
            $criteria = $filters['criteria'] ?? false;
            $query->when($criteria, function($q) use ($criteria) {
              return $q->whereHas('student', function($query) use ($criteria) {
                return $query->where('name', 'like', '%'.$criteria.'%')
                    ->orWhere('first_name', 'like', '%'.$criteria.'%')
                    ->orWhere('middle_name', 'like', '%'.$criteria.'%')
                    ->orWhere('last_name', 'like', '%'.$criteria.'%');
              });
            });

            $orderBy = $filters['order_by'] ?? false;
            $query->when($orderBy, function($q) use ($orderBy, $filters) {
                $sort = $filters['sort'] ?? 'ASC';
                return $q->orderBy($orderBy, $sort);
            });

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

    public function storeBatchSoa(array $data)
    {
        DB::beginTransaction();
        try {
            $studentFees = Term::find($data['term_id'])
                ->studentFees()
                ->with('academicRecord')
                ->wherePivot('is_billed', 0);

            $levelId = $data['level_id'] ?? false;
            $studentFees->when($levelId, function ($query) use ($levelId) {
                $query->whereHas('academicRecord', function ($q) use ($levelId) {
                    return $q->where('level_id', $levelId);
                });
            });

            $billings = [];
            foreach ($studentFees->get() as $studentFee) {
                $billing = Billing::create([
                    'total_amount' => $studentFee->pivot->amount,
                    'student_id' => $studentFee->student_id,
                    'due_date' => $data['due_date'],
                    'term_id' => $data['term_id'],
                    'billing_type_id' => 2,
                    'billing_status_id' => 2,
                    'school_year_id' => $studentFee->school_year_id,
                    'semester_id' => $studentFee->semester_id,
                    'student_fee_id' => $studentFee->id,
                    'previous_balance' => $studentFee->getPreviousBalance()
                ]);

                $billing->update([
                    'billing_no' => 'BILL-'. date('Y') .'-'. str_pad($billing->id, 7, '0', STR_PAD_LEFT)
                ]);

                $billings[] = $billing;
            }
            $studentFees->update([
                'is_billed' => 1
            ]);
            DB::commit();
            return $billings;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SchoolFeeService storeBatchSoa method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $billing = Billing::create($data);
            $billing->update([
                'billing_no' => 'BILL-'. date('Y') .'-'. str_pad($billing->id, 7, '0', STR_PAD_LEFT)
            ]);
            // if billing is soa update student_fee_term is_billed to 1
            if ($billing->billing_type_id === 2) {
                $billing->studentFee()->first()->terms()->wherePivot('term_id', $billing->term_id)
                    ->update(['is_billed' => 1]);
            }

            DB::commit();
            return $billing;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during BillingService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
