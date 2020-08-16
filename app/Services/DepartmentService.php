<?php

namespace App\Services;

use App\Department;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DepartmentService
{
    public function index(object $data)
    {
        try {
            $perPage = $data->per_page ?? 20;
            $departments = !$data->has('paginate') || $data->paginate === 'true'
                ? Department::paginate($perPage)
                : Department::all();
            return $departments;
        } catch (Exception $e) {
            Log::info('Error occured during DepartmentService index method call: ');
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

    public function update(array $data, Department $department)
    {
        DB::beginTransaction();
        try {
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

    public function delete(Department $department)
    {
        try {
            $department->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during DepartmentService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        } 
    }
}
