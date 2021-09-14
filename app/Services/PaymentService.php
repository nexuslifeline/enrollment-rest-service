<?php

namespace App\Services;

use App\Payment;
use App\Student;
use App\StudentFee;
use Carbon\Carbon;
use Exception;
use Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function list(bool $isPaginated, int $perPage, array $filters)
    {
        try {
            //added billing related model
            //10 10 2020
            $query = Payment::with(['paymentMode', 'billing', 'student' => function ($query) {
                $query->with(['address', 'photo', 'user']);
            }])
                ->where('payment_status_id', '!=', 1);
            //filter

            //student
            $studentId = $filters['student_id'] ?? false;
            $query->when($studentId, function ($q) use ($studentId) {
                return $q->where('student_id', $studentId);
            });

            //payment status
            $paymentStatusId = $filters['payment_status_id'] ?? false;
            $query->when($paymentStatusId, function ($q) use ($paymentStatusId) {
                return $q->where('payment_status_id', $paymentStatusId);
            });

            $dateFrom = $filters['date_from'] ?? false;
            $dateTo = $filters['date_to'] ?? false;
            $query->when($dateFrom, function ($q) use ($dateFrom, $dateTo) {
                return $q->whereBetween('date_paid', [$dateFrom, $dateTo]);
            });

            //criteria
            $criteria = $filters['criteria'] ?? false;
            $query->when($criteria, function ($q) use ($criteria) {
                return $q->where(function ($q) use ($criteria) {
                    return $q->whereLike($criteria)
                        ->orWhereHas('student', function ($query) use ($criteria) {
                            // return $query->where(function ($q) use ($criteria) {
                            //     return $q->where('name', 'like', '%' . $criteria . '%')
                            //         ->orWhere('student_no', 'like', '%' . $criteria . '%')
                            //         ->orWhere('first_name', 'like', '%' . $criteria . '%')
                            //         ->orWhere('middle_name', 'like', '%' . $criteria . '%')
                            //         ->orWhere('last_name', 'like', '%' . $criteria . '%');
                            // });

                            //scopedWhereLike on student model
                            $query->whereLike($criteria);
                        });
                });
            });

            // order by
            $orderBy = 'id';
            $sort = 'DESC';

            $ordering = $filters['ordering'] ?? false;
            if ($ordering) {
                $isDesc = str_starts_with($ordering,
                    '-'
                );
                $orderBy = $isDesc ? substr($ordering, 1) : $ordering;
                $sort = $isDesc ? 'DESC' : 'ASC';
            }
            $studentFields = ['first_name', 'last_name', 'complete_address', 'city', 'barangay', 'region'];

            if (in_array($orderBy, $studentFields)) {
                $query->orderByStudent($orderBy, $sort);
            } else {
                $query->orderBy($orderBy, $sort);
            }

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
            $payment->load(['paymentMode', 'files', 'paymentReceiptFiles', 'student' => function ($query) {
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
            $enrolledStatus = Config::get('constants.academic_record_status.ENROLLED');
            $billing = $payment->billing;
            //check if billing is initial
            if ($billing->billing_type_id === 1) {
                $student = $payment->student;
                $academicRecord = $student->academicRecords()->get()->last();
                // update application status and step to completed and waiting
                // if ($academicRecord['is_manual'] === 1) {
                //     // $academicRecord->application->update([
                //     //     'application_status_id' => 7,
                //     //     'application_step_id' => 10
                //     // ]);

                //     //update academic record status to enrolled
                //     $academicRecord->update([
                //         'academic_record_status_id' => $enrolledStatus
                //     ]);
                // }
                //check if student is new or old
                if ($academicRecord['student_category_id'] === 1) {
                    $students = Student::with(['academicRecords'])
                        ->whereHas('academicRecords', function ($query) use ($enrolledStatus) {
                            return $query->where('student_category_id', 1)
                                ->where('academic_record_status_id', $enrolledStatus);
                        })
                        ->get();

                    $student->update([
                        'student_no' => '11' . str_pad(count($students) + 1, 8, '0', STR_PAD_LEFT)
                    ]);
                }

                $studentFee = StudentFee::find($billing->student_fee_id);
                $studentFee->recomputeTerms($payment->amount);
            }

            DB::commit();
            // $payment->load(['billing' => function($q) {
            //     $q->append('total_paid');
            // }]);
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
            $enrolledStatus = Config::get('constants.academic_record_status.ENROLLED');
            $paymentStatusId = $data['payment_status_id'] ?? false;
            if ($paymentStatusId === 2) {
                $student = $payment->student()->first();
                $academicRecord = $student->academicRecords()->get()->last();
                //check if student is new or old
                if ($academicRecord['student_category_id'] === 1) {
                    $students = Student::with(['academicRecords'])
                        ->whereHas('academicRecords', function ($query) use ($enrolledStatus) {
                            return $query->where('student_category_id', 1)
                                ->where('academic_record_status_id', $enrolledStatus);
                        })
                        ->get();

                    $student->update([
                        'student_no' => '11' . str_pad(count($students) + 1, 8, '0', STR_PAD_LEFT)
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
            $billing = $payment->billing;
            $payment->delete();
            $totalPayments = $billing->payments->sum('amount');
            $billingStatusId = $totalPayments > 0 ? Config::get('constants.billing_status.PARTIALLY_PAID') : Config::get('constants.billing_status.UNPAID');
            $billing->update([
                'billing_status_id' => $billingStatusId
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during PaymentService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function submitPayment(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $payment = Payment::find($id);
            $data['payment_status_id'] = Config::get('constants.payment_status.PENDING');
            $payment->update($data);

            $initialBillingType = Config::get('constants.billing_type.INITIAL_FEE');
            $billing = $payment->billing;
            $studentFee = $billing->studentFee;
            if ($billing && $billing->billing_type_id === $initialBillingType && $studentFee && $studentFee->academicRecord) {
                $paymentSubmitted = Config::get('constants.academic_record_status.PAYMENT_SUBMITTED');
                $studentFee->academicRecord->update([
                    'academic_record_status_id' => $paymentSubmitted
                ]);
            }

            $student = $payment->student;
            if ($student && $student->is_onboarding) {
                $paymentInReview = Config::get('constants.onboarding_step.PAYMENT_IN_REVIEW');
                $student->update([
                    'onboarding_step_id' => $paymentInReview
                ]);
            }
            DB::commit();
            return $payment;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during PaymentService submitPayment method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function approve(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $payment = Payment::find($id);
            $data['payment_status_id'] = Config::get('constants.payment_status.APPROVED');
            $data['approved_date'] = Carbon::now();
            $data['approved_by'] = Auth::id();
            $payment->update($data);
            $billing = $payment->billing;

            $billingStatusPaid = Config::get('constants.billing_status.PAID');
            $billingStatusPartiallyPaid = Config::get('constants.billing_status.PARTIALLY_PAID');

            $billing->update([
                'billing_status_id' => $payment->amount < $billing->total_amount + $billing->previous_balance ? $billingStatusPartiallyPaid : $billingStatusPaid
            ]);

            $studentFee = $billing->studentFee;
            $initialBillingType = Config::get('constants.billing_type.INITIAL_FEE');
            if ($billing->billing_type_id === $initialBillingType && $studentFee && $studentFee->academicRecord) {
                $enrolledStatus = Config::get('constants.academic_record_status.ENROLLED');
                $academicRecord = $studentFee->academicRecord;
                $student = $academicRecord->student;
                $academicRecord = $student->academicRecords()->get()->last();
                //check if student is new or old
                if ($academicRecord['student_category_id'] === 1) {
                    $enrolledStatus = Config::get('constants.academic_record_status.ENROLLED');
                    $students = Student::with(['academicRecords'])
                    ->whereHas('academicRecords', function ($query) use ($enrolledStatus) {
                        return $query->where('student_category_id', 1)
                        ->where('academic_record_status_id', $enrolledStatus);
                    })
                        ->get();

                    $student->update([
                        'student_no' => '11' . str_pad(count($students) + 1, 8, '0', STR_PAD_LEFT)
                    ]);
                }

                $studentFee->academicRecord->update([
                    'academic_record_status_id' => $enrolledStatus,
                    'is_initial_billing_paid' => 1
                ]);

                $studentFee->recomputeTerms($payment->amount);
            }
            DB::commit();
            return $payment;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during PaymentService approve method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function reject(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $payment = Payment::find($id);
            $data['payment_status_id'] = Config::get('constants.payment_status.REJECTED');
            $data['disapproved_date'] = Carbon::now();
            $data['disapproved_by'] = Auth::id();
            $payment->update($data);
            $student = $payment->student;
            if($student && $student->is_onboarding) {
                $payments = Config::get('constants.onboarding_step.PAYMENTS');
                $student->update([
                    'onboarding_step_id' => $payments
                ]);
            }

            $initialBillingType = Config::get('constants.billing_type.INITIAL_FEE');
            $billing = $payment->billing;
            $studentFee = $billing->studentFee;
            if ($billing && $billing->billing_type_id === $initialBillingType && $studentFee && $studentFee->academicRecord) {
                $paymentSubmitted = Config::get('constants.academic_record_status.PAYMENT_SUBMITTED');
                $studentFee->academicRecord->update([
                    'academic_record_status_id' => $paymentSubmitted
                ]);
            }
            
            DB::commit();
            return $payment;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during PaymentService reject method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function cancel(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $payment = Payment::find($id);
            $payment->update($data);
            $payment->billing->studentFee->recomputeTerms();
            $billing = $payment->billing;
            $payment->delete();
            $totalPayments = $billing->payments->sum('amount');
            $billingStatusId = $totalPayments > 0 ? Config::get('constants.billing_status.PARTIALLY_PAID') : Config::get('constants.billing_status.UNPAID');
            $billing->update([
                'billing_status_id' => $billingStatusId
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during PaymentService cancel method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getPaymentsOfBilling(int $billingId, bool $isPaginated, int $perPage, array $filters)
    {
        try {
            $query = Payment::where('billing_id', $billingId);

            //payment mode 
            $paymentModeId = $filters['payment_mode_id'] ?? false;
            $query->when($paymentModeId, function ($q) use ($paymentModeId) {
                return $q->where('payment_mode_id', $paymentModeId);
            });

            //payment status 
            $paymentStatusId = $filters['payment_status_id'] ?? false;
            $query->when($paymentStatusId, function ($q) use ($paymentStatusId) {
                return $q->where('payment_status_id', $paymentStatusId);
            });

            //criteria
            //criteria
            $criteria = $filters['criteria'] ?? false;
            $query->when($criteria, function ($q) use ($criteria) {
                return $q->where(function ($q) use ($criteria) {
                    return $q->whereLike($criteria);
                });
            });

            $payments = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();
            return $payments;
        } catch (Exception $e) {
            Log::info('Error occured during PaymentService getPaymentsOfBilling method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
