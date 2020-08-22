<?php

namespace App\Services;

use App\Department;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DepartmentService
{
    public function list(bool $isPaginated, int $perPage)
    {
        try {
            $departments = $isPaginated
                ? Department::paginate($perPage)
                : Department::all();
            return $departments;
        } catch (Exception $e) {
            Log::info('Error occured during DepartmentService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $department = Department::find($id);
            return $department;
        } catch (Exception $e) {
            Log::info('Error occured during DepartmentService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $department = Department::create($data);  
            DB::commit();
            return $department;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during DepartmentService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $department = Department::find($id);
            $department->update($data); 
            DB::commit();
            return $department;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during DepartmentService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        try {
            $department = Department::find($id);
            $department->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during DepartmentService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        } 
    }
}
