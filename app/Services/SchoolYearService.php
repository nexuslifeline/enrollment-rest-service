<?php

namespace App\Services;

use App\AcademicRecord;
use App\Billing;
use Exception;
use App\SchoolYear;
use App\Term;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SchoolYearService
{
    public function list(bool $isPaginated, int $perPage, array $filters, array $ordering = [])
    {
        try {

            $isActive = $filters['is_active'] ?? false;
            $query = SchoolYear::when($isActive, function($q) use ($isActive){
                return $q->where('is_active', $isActive);
            });

            if (!Arr::exists($ordering, 'columns')) $ordering['columns'] = 'id';
            if (!Arr::exists($ordering, 'order_by')) $ordering['order_by'] = 'DESC';

            $query->orderBy($ordering['columns'], $ordering['order_by']);

            $schoolYears = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();

            return $schoolYears;
        } catch (Exception $e) {
            Log::info('Error occured during SchoolYearService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $schoolYear = SchoolYear::find($id);
            $schoolYear->load('schoolCategoryModes');
            return $schoolYear;
        } catch (Exception $e) {
            Log::info('Error occured during SchoolYearService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            if (!Arr::exists($data, 'is_active')) {
                $data['is_active'] = 1;
            }

            $schoolYear = SchoolYear::create($data);
            if ($data['is_active']) {
                $activeSchoolYear = SchoolYear::where('id', '!=', $schoolYear->id)
                ->where('is_active', 1);
                $activeSchoolYear->update([
                    'is_active' => 0
                ]);
            }
            DB::commit();
            return $schoolYear;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SchoolYearService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, int $id = 0)
    {
        DB::beginTransaction();
        try {
            $schoolYear = $id ? SchoolYear::find($id) : SchoolYear::query(); // update all if no id is provided
            $schoolYear->update($data);
            if (Arr::exists($data, 'is_active') && $data['is_active']) {
                $activeSchoolYear = SchoolYear::where('id', '!=', $schoolYear->id)
                ->where('is_active', 1);
                $activeSchoolYear->update([
                    'is_active' => 0
                ]);
            }
            DB::commit();
            return $schoolYear;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SchoolYearService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        try {
            $schoolYear = SchoolYear::find($id);
            $schoolYear->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SchoolYearService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function generateBatchBilling(array $data, array $otherFees, int $schoolYearId)
    {
        DB::beginTransaction();
        try {
            $schoolCategoryId = $data['school_category_id'] ?? false;

            $soaBillingType = Config::get('constants.billing_type.SOA');
            $otherBillingType = Config::get('constants.billing_type.BILL');
            $unpaidStatus = Config::get('constants.billing_status.UNPAID');
            $partiallyPaidStatus = Config::get('constants.billing_status.PARTIALLY_PAID');
            $enrolledStatus = Config::get('constants.academic_record_status.ENROLLED');

            if ($data['billing_type_id'] === $otherBillingType && !$otherFees) {
                throw ValidationException::withMessages([
                    'non_field_error' => ['Other Fees must have atleast one item.']
                ]);
            }

            if (!$schoolYearId) {
                throw ValidationException::withMessages([
                    'school_year_id' => ['School year field is required.']
                ]);
            }

            $billings = [];

            $totalBillingItems = array_reduce($otherFees, function ($carry, $item) {
                return $carry + $item['amount'];
            });
            
            if ($data['billing_type_id'] === $soaBillingType) {
                if ($schoolCategoryId) {
                    $terms = Term::where('school_category_id', $schoolCategoryId)
                        ->get();

                    if ($terms->count() === 0) {
                        throw ValidationException::withMessages([
                            'non_field_error' => ['School category doesn\'t have billing terms. You need to create a biling terms first.']
                        ]);
                    }
                }

                $studentFees = Term::find($data['term_id'])
                ->studentFees()
                ->with('academicRecord')
                ->wherePivot('is_billed', 0);

                $levelId = $data['level_id'] ?? false;
                $studentFees->when($levelId, function ($query) use ($levelId, $schoolYearId, $enrolledStatus) {
                    $query->whereHas('academicRecord', function ($q) use ($levelId, $enrolledStatus, $schoolYearId) {
                        return $q->where('level_id', $levelId)
                        ->where('school_year_id', $schoolYearId)
                        ->where('academic_record_status_id', $enrolledStatus);
                    });
                });

                foreach ($studentFees->get() as $studentFee) {
                    $billing = Billing::create([
                        'total_amount' => $studentFee->pivot->amount + $totalBillingItems,
                        'student_id' => $studentFee->academicRecord->student_id,
                        'due_date' => $data['due_date'],
                        'term_id' => $data['term_id'],
                        'billing_type_id' => $soaBillingType,
                        'billing_status_id' => $unpaidStatus,
                        'academic_record_id' => $studentFee->academic_record_id,
                        'student_fee_id' => $studentFee->id,
                        'previous_balance' => $studentFee->getPreviousBalance()
                    ]);

                    $billing->update([
                        'billing_no' => 'BILL-' . date('Y') . '-' . str_pad($billing->id, 7, '0', STR_PAD_LEFT)
                    ]);

                    foreach ($otherFees as $item) {
                        $billing->billingItems()->create([
                            'school_fee_id' => $item['school_fee_id'],
                            'amount' => $item['amount']
                        ]);
                    }

                    $billing->billingItems()->create([
                        'term_id' => $billing->term_id,
                        'amount' => $studentFee->pivot->amount
                    ]);

                    $soaBillings = Billing::where('billing_type_id', $soaBillingType)
                    ->where('student_fee_id', $billing->studentFee->id)
                    ->whereIn('billing_status_id', [$unpaidStatus, $partiallyPaidStatus])
                    ->where('id', '!=', $billing->id)
                    ->where('is_forwarded', 0)
                    ->get();

                    foreach ($soaBillings as $soaBilling) {
                        $soaBilling->update([
                            'is_forwarded' => 1,
                            'system_notes' => 'Balance forwarded to Billing ' . $billing['billing_no'] . ' on ' . Carbon::now()->format('F d, Y') . ' amounting to ' . number_format($soaBilling->total_remaining_due, 2)
                        ]);
                    }

                    $billings[] = $billing;
                }
                $studentFees->update([
                    'is_billed' => 1
                ]);
            } else {
                $academicRecords = AcademicRecord::where('school_category_id', $data['school_category_id'])
                ->where('school_year_id', $schoolYearId)
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
                        'total_amount' => $totalBillingItems,
                        'student_id' => $academicRecord['student_id'],
                        'billing_type_id' => $otherBillingType,
                        'billing_status_id' => $unpaidStatus,
                        'academic_record_id' => $academicRecord['id']
                    ]);
                    $billing->update([
                        'billing_no' => 'BILL-' . date('Y') . '-' . str_pad($billing->id, 7, '0', STR_PAD_LEFT)
                    ]);

                    foreach ($otherFees as $item) {
                        $billing->billingItems()->create([
                            'school_fee_id' => $item['school_fee_id'],
                            'amount' => $item['amount']
                        ]);
                    }
                    $billings[] = $billing;
                }
            }
            DB::commit();
            return $billings;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SchoolYearService generateBatchBilling method call: ');
            Log::info($e->getMessage());
            throw $e;
        } 
    }
}
