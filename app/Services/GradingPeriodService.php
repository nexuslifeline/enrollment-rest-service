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
            $query = GradingPeriod::with(['schoolYear', 'schoolCategory','semester']);

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

            // filter by Semester id
            $semesterId = $filters['semester_id'] ?? false;
            $query->when($semesterId, function ($q) use ($semesterId) {
                return $q->where('semester_id', $semesterId);
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
            $gradingPeriod->load(['schoolYear', 'schoolCategory', 'semester']);
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
            $gradingPeriod->load(['schoolYear', 'schoolCategory', 'semester']);
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
            $gradingPeriod->load(['schoolYear', 'schoolCategory', 'semester']);
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

    public function updateCreateBulk(array $gradingPeriods, array $filters)
    {
        DB::beginTransaction();
        try {

            $schoolYearId = $filters['school_year_id'] ?? null;
            $schoolCategoryId = $filters['school_category_id'] ?? null;
            $semesterId = $filters['semester_id'] ?? null;

            foreach ($gradingPeriods as $period) {
                GradingPeriod::updateOrCreate(
                    ['id' => $period['id']],
                    [
                        'name' => $period['name'],
                        'description' => $period['description'],
                        'school_year_id' => $schoolYearId,
                        'school_category_id' => $schoolCategoryId,
                        'semester_id' => $semesterId,
                    ]
                );
            }

            DB::commit();

            $query = GradingPeriod::where('school_year_id', $schoolYearId)
                ->where('school_category_id', $schoolCategoryId)
                ->where('semester_id', $semesterId);

            return $query->get();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during GradingPeriodService updateCreateBulk method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}