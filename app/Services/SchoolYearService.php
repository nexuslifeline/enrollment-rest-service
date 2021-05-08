<?php

namespace App\Services;

use Exception;
use App\SchoolYear;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchoolYearService
{
    public function list(bool $isPaginated, int $perPage, array $filters)
    {
        try {

            $isActive = $filters['is_active'] ?? false;
            $query = SchoolYear::when($isActive, function($q) use ($isActive){
                return $q->where('is_active', $isActive);
            });

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
}
