<?php

namespace App\Services;

use Exception;
use App\SchoolCategoryMode;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchoolCategoryModeService
{
    public function list(bool $isPaginated, int $perPage)
    {
        try {
            $schoolCategoryMode = $isPaginated
                ? SchoolCategoryMode::paginate($perPage)
                : SchoolCategoryMode::all();
            return $schoolCategoryMode;
        } catch (Exception $e) {
            Log::info('Error occured during SchoolCategoryModeService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $gradingPeriod = SchoolCategoryMode::create($data);
            DB::commit();
            return $gradingPeriod;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SchoolCategoryModeService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $gradingPeriod = SchoolCategoryMode::find($id);
            return $gradingPeriod;
        } catch (Exception $e) {
            Log::info('Error occured during SchoolCategoryModeService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            // if school category with school year id does not exists, create it
            $schoolYearId = $data['school_year_id'];
            $semesterId = Arr::exists($data, 'semester_id')
                ? $data['semester_id']
                : null;

            $gradingPeriod = SchoolCategoryMode::updateOrCreate(
                [
                    'school_category_id' => $id,
                    'school_year_id' => $schoolYearId,
                    'semester_id' => $semesterId
                ],
                $data
            );

            DB::commit();
            return $gradingPeriod;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SchoolCategoryModeService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        DB::beginTransaction();
        try {
            $gradingPeriod = SchoolCategoryMode::find($id);
            $gradingPeriod->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SchoolCategoryModeService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}