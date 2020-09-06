<?php

namespace App\Services;

use App\Http\Requests\RateSheetStoreRequest;
use App\RateSheet;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RateSheetService
{
    public function list(bool $isPaginated, int $perPage, array $filter)
    {
        try {
            $perPage = $filter['per_page'] ?? 20;
            $query = RateSheet::with(['level', 'course', 'semester', 'fees']);

            // filters
            $levelId = $filter['level_id'] ?? false;
            $query->when($levelId, function($q) use ($levelId) {
                return $q->where('level_id', $levelId);
            });

            $courseId = $filter['course_id'] ?? false;
            $query->when($courseId, function($q) use ($courseId) {
                return $q->where('course_id', $courseId);
            });

            $semesterId = $filter['semester_id'] ?? false;
            $query->when($semesterId, function($q) use ($semesterId) {
                return $q->where('semester_id', $semesterId);
            });

            $rateSheets = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();

            return $rateSheets;

        } catch (Exception $e) {
            Log::info('Error occured during RateSheetService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $rateSheet = RateSheet::find($id);
            $rateSheet->load(['level', 'course', 'semester', 'fees']);
            return $rateSheet;
        } catch (Exception $e) {
            Log::info('Error occured during RateSheetService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(array $data, array $fees)
    {
        DB::beginTransaction();
        try {
            $rateSheet = RateSheet::create($data);

            // if ($fees) {
            $items = [];
            foreach ($fees as $fee) {
                $items[$fee['school_fee_id']] = [
                    'amount' => $fee['amount'],
                    'notes' => $fee['notes']
                ];
            }
            $rateSheet->fees()->sync($items);
            // }

            $rateSheet->load(['level', 'course', 'semester', 'fees']);
            DB::commit();
            return $rateSheet;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during RateSheetService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, array $fees, int $id)
    {
        DB::beginTransaction();
        try {
            $rateSheet = RateSheet::find($id);
            $rateSheet->update($data);

            // if ($fees) {
            $items = [];
            foreach ($fees as $fee) {
                $items[$fee['school_fee_id']] = [
                    'amount' => $fee['amount'],
                    'notes' => $fee['notes']
                ];
            }
            $rateSheet->fees()->sync($items);
            // }

            $rateSheet->load(['level', 'course', 'semester', 'fees']);
            DB::commit();
            return $rateSheet;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during RateSheetService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        try {
            $rateSheet = RateSheet::find($id);
            $rateSheet->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during RateSheetService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
