<?php

namespace App\Services;

use App\Semester;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SemesterService
{
    public function list(bool $isPaginated, int $perPage, array $filters)
    {
        try {

            $isActive = $filters['is_active'] ?? false;
            $query = Semester::when($isActive, function($q) use ($isActive){
                return $q->where('is_active', $isActive);
            });

            $semesters = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();

            return $semesters;
        } catch (Exception $e) {
            Log::info('Error occured during SemesterService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $semester = Semester::find($id);
            $semester->update($data);
            if ($data['is_active']) {
                $activeSemester = Semester::where('id', '!=', $semester->id)
                ->where('is_active', 1);
                $activeSemester->update([
                    'is_active' => 0
                ]);
            }
            DB::commit();
            return $semester;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SemesterService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}