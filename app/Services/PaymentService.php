<?php

namespace App\Services;

use App\Payment;
use App\Student;
use App\StudentFee;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function list(bool $isPaginated, int $perPage, array $filters)
    {
        try {
            //added billing related model
            //10 10 2020
            $query = Payment::with(['paymentMode', 'billing', 'student' => function($query) {
                $query->with(['address', 'photo']);
            }])
            ->where('payment_status_id', '!=', 1);
            //filter
            //payment status
            $paymentStatusId = $filters['payment_status_id'] ?? false;
            $query->when($paymentStatusId, function($q) use ($paymentStatusId) {
                    return $q->where('payment_status_id', $paymentStatusId);
            });

            $dateFrom = $filters['date_from'] ?? false;
            $dateTo = $filters['date_to'] ?? false;
            $query->when($dateFrom, function($q) use ($dateFrom, $dateTo) {
                return $q->whereBetween('date_paid', [$dateFrom, $dateTo]);
            });

            //criteria
            $criteria = $filters['criteria'] ?? false;
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

            // order by
            $orderBy = $filters['order_by'] ?? false;
            $query->when($orderBy, function($q) use ($orderBy, $filters) {
                $sort = $filters['sort'] ?? 'ASC';
                return $q->orderBy($orderBy, $sort);
            });

            $payments = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();
            return $payments;
        } catch (Exception $e) {
            Log::info('Error occured during PaymentService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
          $payment = Payment::find($id);
          $payment->load(['paymentMode', 'student' => function($query) {
              $query->with(['address', 'photo']);
          }]);
          return $payment;
        } catch (Exception $e) {
          Log::info('Error occured during PaymentService get method call: ');
          Log::info($e->getMessage());
          throw $e;
      }
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $payment = Payment::create($data);
            DB::commit();
            $payment->load(['billing' => function($q) {
                $q->append('total_paid');
            }]);
            return $payment;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during PaymentService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $payment = Payment::find($id);
            $payment->update($data);
            $paymentStatusId = $data['payment_status_id'] ?? false;
            if ($paymentStatusId === 2) {
                $student = $payment->student()->first();
                $academicRecord = $student->academicRecords()->get()->last();
                //check if student is new or old
                if ($academicRecord['student_category_id'] === 1) {
                    $students = Student::with(['academicRecords'])
                    ->whereHas('academicRecords', function ($query) {
                        return $query->where('student_category_id',1)
                        ->where('academic_record_status_id', 3);
                    })
                    ->get();

                    $student->update([
                        'student_no' => '11'. str_pad(count($students) + 1, 8, '0', STR_PAD_LEFT)
                    ]);
                }
                $billing = $payment->billing;
                if ($billing->billing_type_id === 1) {
                    $studentFee = StudentFee::find($billing->student_fee_id);
                    $studentFee->recomputeTerms($payment->amount);
                }
            }
            DB::commit();
            return $payment;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during PaymentService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        DB::beginTransaction();
        try {
            $payment = Payment::find($id);
            $payment->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during PaymentService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
