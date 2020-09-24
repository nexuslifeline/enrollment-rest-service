<?php

namespace App\Services;

use App\SchoolYear;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchoolYearService
{
    public function list(bool $isPaginated, int $perPage)
    {
        try {
            $schoolYears = $isPaginated
                ? SchoolYear::paginate($perPage)
                : SchoolYear::all();
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

    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $schoolYear = SchoolYear::find($id);
            $schoolYear->update($data);
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
