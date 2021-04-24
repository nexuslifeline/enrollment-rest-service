<?php

namespace App\Services;

use App\GradingPeriod;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GradingPeriodService
{
    public function list(bool $isPaginated, int $perPage, array $filters)
    {
        try {
            $query = GradingPeriod::with(['schoolYear', 'schoolCategory']);

            // filter by School Year id
            $schoolYearId = $filters['school_year_id'] ?? false;
            $query->when($schoolYearId, function ($q) use ($schoolYearId) {
                return $q->where('school_year_id', $schoolYearId);
            });

            // filter by School Category id
            $schoolCategoryId = $filters['school_category_id'] ?? false;
            $query->when($schoolCategoryId, function ($q) use ($schoolCategoryId) {
                return $q->where('school_category_id', $schoolCategoryId);
            });

            $gradingPeriods = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();
            return $gradingPeriods;
        } catch (Exception $e) {
            Log::info('Error occured during GradingPeriodService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $gradingPeriod = GradingPeriod::create($data);
            $gradingPeriod->load(['schoolYear', 'schoolCategory']);
            DB::commit();
            return $gradingPeriod;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during GradingPeriodService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $gradingPeriod = GradingPeriod::find($id);
            $gradingPeriod->load(['schoolYear', 'schoolCategory']);
            return $gradingPeriod;
        } catch (Exception $e) {
            Log::info('Error occured during GradingPeriodService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $gradingPeriod = GradingPeriod::find($id);
            $gradingPeriod->update($data);
            DB::commit();
            return $gradingPeriod;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during GradingPeriodService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        DB::beginTransaction();
        try {
            $gradingPeriod = GradingPeriod::find($id);
            $gradingPeriod->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during GradingPeriodService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}