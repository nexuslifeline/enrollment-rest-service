<?php

namespace App\Services;

use App\AcademicRecord;
use App\Billing;
use App\Payment;
use App\SchoolYear;
use App\Term;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class BillingService
{
    public function list(bool $isPaginated, int $perPage, array $filters)
    {
        try {
            $query = Billing::with([
                'billingType',
                'studentFee',
                'payments',
                'term',
                'academicRecord'  => function ($q) {
                    return $q->with(['semester', 'schoolYear', 'level']);
                },
                'student' => function ($q) {
                    return $q->with(['photo']);
                }
            ]);
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
                return $q->whereHas('academicRecord', function ($q) use ($schoolYearId) {
                    return $q->whereHas('schoolYear', function ($query) use ($schoolYearId) {
                        return $query->where('school_year_id', $schoolYearId);
                    });
                });
            });
            // semester
            $semesterId = $filters['semester_id'] ?? false;
            $query->when($semesterId, function ($q) use ($semesterId) {
                return $q->whereHas('academicRecord', function ($q) use ($semesterId) {
                    return $q->whereHas('semester', function ($query) use ($semesterId) {
                        return $query->where('semester_id', $semesterId);
                    });
                });
            });
            // school category
            $schoolCategoryId = $filters['school_category_id'] ?? false;
            $query->when($schoolCategoryId, function ($q) use ($schoolCategoryId) {
                $q->where(function ($q) use ($schoolCategoryId) {
                    return $q->whereHas('studentFee', function ($query) use ($schoolCategoryId) {
                        return $query->whereHas('academicRecord', function ($q) use ($schoolCategoryId) {
                            return $q->where('school_category_id', $schoolCategoryId);
                        });
                    })->orWhere(function ($q) use ($schoolCategoryId) {
                        return $q->whereDoesntHave('studentFee')
                        ->whereHas('student', function ($q) use ($schoolCategoryId) {
                            return $q->whereHas('academicRecords', function ($q) use ($schoolCategoryId) {
                                return $q->where('school_category_id', $schoolCategoryId);
                            });
                        });
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
            $query->when($criteria, function ($q) use ($criteria) {
                return $q->whereHas('student', function ($query) use ($criteria) {
                    return $query->whereLike($criteria);
                })->orWhere('system_notes', 'like', '%' . $criteria . '%');

            });

            $orderBy = $filters['order_by'] ?? false;
            $query->when($orderBy, function ($q) use ($orderBy, $filters) {
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
            $billing->load(['billingType', 'student' => function($q) {
                return $q->with('latestAcademicRecord');
            }, 'billingItems' => function ($q) {
                return $q->with('schoolFee');
            }]);
            $billing->append(['total_paid']);
            return $billing;
        } catch (Exception $e) {
            Log::info('Error occured during BillingService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function storeBatchSoa(array $data, array $billingItems)
    {
        DB::beginTransaction();
        try {
            $studentFees = Term::find($data['term_id'])
                ->studentFees()
                ->with('academicRecord')
                ->wherePivot('is_billed', 0);

            $levelId = $data['level_id'] ?? false;
            $enrolledStatus = Config::get('constants.academic_record_status.ENROLLED');
            $studentFees->when($levelId, function ($query) use ($levelId, $enrolledStatus) {
                $query->whereHas('academicRecord', function ($q) use ($levelId, $enrolledStatus) {
                    return $q->where('level_id', $levelId)
                    ->where('academic_record_status_id', $enrolledStatus);
                });
            });

            $billings = [];

            $totalBillingItems = array_reduce($billingItems, function ($carry, $item) {
                return $carry + $item['amount'];
            });

            foreach ($studentFees->get() as $studentFee) {
                $billing = Billing::create([
                    'total_amount' => $studentFee->pivot->amount + $totalBillingItems,
                    'student_id' => $studentFee->student_id,
                    'due_date' => $data['due_date'],
                    'term_id' => $data['term_id'],
                    'billing_type_id' => 2,
                    'billing_status_id' => 2,
                    'academic_record_id' => $studentFee->academic_record_id,
                    // 'school_year_id' => $studentFee->school_year_id,
                    // 'semester_id' => $studentFee->semester_id,
                    'student_fee_id' => $studentFee->id,
                    'previous_balance' => $studentFee->getPreviousBalance()
                ]);

                $billing->update([
                    'billing_no' => 'BN-' . date('Y') . '-' . str_pad($billing->id, 7, '0', STR_PAD_LEFT)
                ]);

                foreach ($billingItems as $item) {
                    $billing->billingItems()->create($item);
                }

                $billing->billingItems()->create([
                    'term_id' => $billing->term_id,
                    'amount' => $studentFee->pivot->amount
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

    public function storeBatchOtherBilling(array $data, array $billingItems)
    {
        DB::beginTransaction();
        try {
            $enrolledStatus = Config::get('constants.academic_record_status.ENROLLED');
            $academicRecords = AcademicRecord::where('school_category_id', $data['school_category_id'])
                ->where('school_year_id', $data['school_year_id'])
                ->where('academic_record_status_id', $enrolledStatus);

            $levelId = $data['level_id'] ?? false;
            $academicRecords->when($levelId, function ($query) use ($levelId) {
                $query->where('level_id', $levelId);
            });

            $courseId = $data['course_id'] ?? false;
            $academicRecords->when($courseId, function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            });

            $semesterId = $data['semester_id'] ?? false;
            $academicRecords->when($semesterId, function ($query) use ($semesterId) {
                $query->where('semester_id', $semesterId);
            });

            $billings = [];
            foreach ($academicRecords->get() as $academicRecord) {
                $billing = Billing::create([
                    'due_date' => $data['due_date'],
                    'total_amount' => $data['total_amount'],
                    'student_id' => $academicRecord['student_id'],
                    'billing_type_id' => $data['billing_type_id'],
                    'billing_status_id' => $data['billing_status_id'],
                    'academic_record_id' => $academicRecord['id']
                    // 'school_year_id' => $data['school_year_id'],
                    // 'semester_id' => $data['semester_id']
                ]);
                $billing->update([
                    'billing_no' => 'BN-' . date('Y') . '-' . str_pad($billing->id, 7, '0', STR_PAD_LEFT)
                ]);

                foreach ($billingItems as $item) {
                    $billing->billingItems()->create($item);
                }
                $billings[] = $billing;
            }
            DB::commit();
            return $billings;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SchoolFeeService storeBatchSoa method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(array $data, array $billingItems)
    {
        DB::beginTransaction();
        try {
            $billing = Billing::create($data);
            $billing->update([
                'billing_no' => 'BN-' . date('Y') . '-' . str_pad($billing->id, 7, '0', STR_PAD_LEFT)
            ]);

            foreach ($billingItems as $item) {
                $billing->billingItems()->create($item);
            }

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

    public function update(array $data, array $otherFees, int $id)
    {
        DB::beginTransaction();
        try {

            $billing = Billing::find($id);
            $academicRecord = AcademicRecord::find($billing->academic_record_id);
            $enrolledStatus = Config::get('constants.academic_record_status.ENROLLED');

            if ($academicRecord->academic_record_status_id !== $enrolledStatus) {
                throw ValidationException::withMessages([
                    'non_field_error' => ['Academic record is not enrolled yet.']
                ]);
            }

            if (!$academicRecord->schoolYear->is_active) {
                throw ValidationException::withMessages([
                    'non_field_error' => ['Academic record is not enrolled in current active school year.']
                ]);
            }

            $soaBillingType = Config::get('constants.billing_type.SOA');
            $otherBillingType = Config::get('constants.billing_type.BILL');
            if ($data['billing_type_id'] === $otherBillingType && !$otherFees) {
                throw ValidationException::withMessages([
                    'non_field_error' => ['Other Fees must have atleast one item.']
                ]);
            }

            $amount = Arr::pull($data, 'amount');
            $totalAmount = $data['billing_type_id'] === $soaBillingType ? collect($otherFees)->sum('amount') + $amount : collect($otherFees)->sum('amount');
            $data = Arr::add($data, 'total_amount', $totalAmount);
            $billing->update($data);

            $billing->billingItems()->delete();
            foreach ($otherFees as $item) {
                $billing->billingItems()->create([
                    'amount' => $item['amount'],
                    'school_fee_id' => $item['school_fee_id']
                ]);
            }
            DB::commit();
            return $billing;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during BillingService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        try {
            DB::beginTransaction();
            $billing = Billing::find($id);
            // if billing is soa update student_fee_term is_billed to 0 so you can create it again
            $soa = Config::get('constants.billing_type.SOA');
            $unpaid = Config::get('constants.billing_status.UNPAID');
            if ($billing->billing_type_id === $soa) {
                $latestBilling = Billing::where('billing_type_id', $soa)
                ->where('student_id', $billing->student_id)
                ->where('billing_type_id', $soa)
                ->latest()
                ->first();

                if ($latestBilling->id !== $billing->id) {
                    throw ValidationException::withMessages([
                        'non_field_error' => ["You can't delete this soa. It's already forwarded."]
                    ]);
                }

                if ($latestBilling->billing_status_id !== $unpaid ) {
                    throw ValidationException::withMessages([
                        'non_field_error' => ["SOA cannot be deleted because it has a payment already. You need to cancel the payment first."]
                    ]);
                }

                $billing->studentFee()->first()->terms()->wherePivot('term_id', $billing->term_id)
                    ->update(['is_billed' => 0]);

                $newLatestBilling = Billing::where('billing_type_id', $soa)
                    ->where('student_id', $billing->student_id)
                    ->where('billing_type_id', $soa)
                    ->where('id', '!=', $billing->id)
                    ->latest()
                    ->first();

                if ($newLatestBilling) {
                    $newLatestBilling->update([
                        'is_forwarded' => 0,
                        'system_notes' => ''
                    ]);
                }
            }
            $billing->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during BillingService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getBillingItemsofBilling(int $id)
    {
        try {
            $billing = Billing::find($id);

            $billingItems = $billing->billingItems()
                ->with(['term' => function ($query) {
                    return $query->with(['schoolYear', 'semester']);
                }, 'schoolFee'])->get();

            return $billingItems;
        } catch (Exception $e) {
            Log::info('Error occured during BillingService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function postPayment(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $billing = Billing::find($id)->append('total_paid');
            
            $schoolYearId = $data['school_year_id'] ?? null;
            if (!$schoolYearId) {
                $schoolYearId = SchoolYear::where('is_active', 1)->first()->id;
            }
            $schoolYearId = $schoolYearId ?? $billing->school_year_id;
            $paymentModeId = $data['payment_mode_id'] ?? Config::get('constants.payment_mode.CASH');

            $latestPayment = Payment::where('is_overpay_forwarded', 0)
                ->where('overpay', '>', 0)
                ->latest()
                ->first();

            $forwardedPayment = 0;

            if ($latestPayment) {
                $latestPayment->update([
                    'is_overpay_forwarded' => 1
                ]);
                $forwardedPayment = $latestPayment['overpay'];
                $data = Arr::add($data, 'system_notes', 'Forwarded payment from ' . $latestPayment['reference_no'] . ' paid on ' . date('F d, Y', strtotime($latestPayment['date_paid'])));
            }

            $overpay = max(($data['amount'] + $forwardedPayment) - $billing['total_remaining_due'], 0);

            $data = Arr::add($data, 'forwarded_payment', $forwardedPayment);
            $data = Arr::add($data, 'overpay', $overpay);
            $data = Arr::add($data, 'school_year_id', $schoolYearId);
            $data = Arr::add($data, 'payment_mode_id', $paymentModeId);
            $data = Arr::add($data, 'payment_status_id', Config::get('constants.payment_status.APPROVED'));
            $data = Arr::add($data, 'student_id', $billing->student_id);

            // $data['school_year_id'] = $schoolYearId ?? $billing->school_year_id;
            // $data['payment_mode_id'] = $data['payment_mode_id'] ?? Config::get('constants.payment_mode.CASH');
            // $data['payment_status_id'] = Config::get('constants.payment_status.APPROVED');
            // $data['student_id'] = $billing->student_id;
            $payment = $billing->payments()->create($data);

            $billingStatusPaid = Config::get('constants.billing_status.PAID');
            $billingStatusPartiallyPaid = Config::get('constants.billing_status.PARTIALLY_PAID');

            $billing->update([
                'billing_status_id' => $payment->amount + $billing->total_paid < $billing->total_amount + $billing->previous_balance ? $billingStatusPartiallyPaid : $billingStatusPaid
            ]);
            
            $studentFee = $billing->studentFee;
            $initialBillingType = Config::get('constants.billing_type.INITIAL_FEE');
            if ($billing && $billing->billing_type_id === $initialBillingType && $studentFee && $studentFee->academicRecord) {
                $enrolledStatus = Config::get('constants.academic_record_status.ENROLLED');
                $studentFee->academicRecord->update([
                    'academic_record_status_id' => $enrolledStatus,
                    'is_initial_billing_paid' => 1
                ]);

                $studentFee->recomputeTerms($payment->amount);
            }

            DB::commit();
            return $billing;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during BillingService postPayment method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function updateInitialBilling(array $data, int $academicRecordId, int $billingId)
    {
        DB::beginTransaction();
        try {
            $academicRecord = AcademicRecord::find($academicRecordId);
            if ($academicRecord->academic_record_status_id === Config::get('constants.academic_record_status.ENROLLED')) {
                throw ValidationException::withMessages([
                    'non_field_error' => ["Initial/Registration Fee cannot be updated because the student is already been enrolled."]
                ]);
            }
            $billing = Billing::find($billingId);
            if ($billing->billing_type_id !== Config::get('constants.billing_type.INITIAL_FEE')) {
                throw ValidationException::withMessages([
                    'non_field_error' => ["This billing is not an Initial/Registration Fee."]
                ]);
            }

            $billing->update($data);
            if (Arr::exists($data, 'total_amount')) {
                $billing->billingItems()->where('item', 'Registration Fee')->first()->update([
                    'amount' => $data['total_amount']
                ]);
            }
            DB::commit();
            return $billing;
        } catch (Exception $e) {
            DB::rollBack();
            Log::info('Error occured during AcademicRecordService updateInitialBilling method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function cancelPayments(int $id)
    {
        DB::beginTransaction();
        try {
            $billing = Billing::find($id);
            $soa = Config::get('constants.billing_type.SOA');
            $initialFee = Config::get('constants.billing_type.INITIAL_FEE');
            $latestBilling = Billing::where('billing_type_id', $soa)
            ->where('student_id', $billing->student_id)
                ->where('billing_type_id', $soa)
                ->latest()
                ->first();
                
            // check billing if initial fee
            if ($billing->billing_type_id === $initialFee) {
                $academicRecord = $billing->academicRecord;
                if ($academicRecord->academic_record_status_id === Config::get('constants.academic_record_status.ENROLLED')) {
                    throw ValidationException::withMessages([
                        'non_field_error' => ["Initial/Registration Fee payment(s) cannot be cancelled because the student is already been enrolled."]
                    ]);
                }
            }
            
            // check if billing soa is latest
            if ($latestBilling && $latestBilling->id !== $billing->id) {
                throw ValidationException::withMessages([
                    'non_field_error' => ["Sorry, cancellation of payment is not allowed if there are new billing already created. You need to cancel/remove first all billing that were created."]
                ]);
            }

            $billing->payments()->delete();
            $billingStatusId = Config::get('constants.billing_status.UNPAID');
            $billing->update([
                'billing_status_id' => $billingStatusId,
                'is_forwarded' => 0
            ]);
            DB::commit();
            return $billing;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during BillingService postPayment method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
