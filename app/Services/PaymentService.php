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
            $query = Payment::with(['paymentMode', 'student' => function($query) {
                $query->with(['address', 'photo']);
            }])
            ->where('payment_status_id', '!=', 1);
            //filter
            //payment status
            $paymentStatusId = $filters['payment_status_id'] ?? false;
                $query->when($paymentStatusId, function($q) use ($paymentStatusId) {
                    return $q->where('payment_status_id', $paymentStatusId);
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
            // if ($request->hasFile('files')) {
            //   $files = $request->file('files');
            //   foreach ($files as $file) {
            //       $path = $file->store('files');
            //       $paymentFile = PaymentFile::create([
            //           'payment_id' => $payment->id,
            //           'path' => $path,
            //           'name' => $file->getClientOriginalName(),
            //           'hash_name' => $file->hashName()
            //       ]);
            //   }
            DB::commit();
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
        try {
            $payment = Payment::find($id);
            $payment->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during PaymentService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}