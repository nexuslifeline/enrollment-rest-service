<?php

namespace App\Services;

use App\SchoolFeeCategory;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchoolFeeCategoryService
{
    public function list(bool $isPaginated, int $perPage)
    {
        try {
            $schoolFeeCategories = $isPaginated
                ? SchoolFeeCategory::paginate($perPage)
                : SchoolFeeCategory::all();
            return $schoolFeeCategories;
        } catch (Exception $e) {
            Log::info('Error occured during SchoolFeeCategoryService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $schoolFeeCategory = SchoolFeeCategory::create($data);
            DB::commit();
            return $schoolFeeCategory;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SchoolFeeCategoryService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $schoolFeeCategory = SchoolFeeCategory::find($id);
            return $schoolFeeCategory;
        } catch (Exception $e) {
            Log::info('Error occured during SchoolFeeCategoryService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $schoolFeeCategory = SchoolFeeCategory::find($id);
            $schoolFeeCategory->update($data);
            DB::commit();
            return $schoolFeeCategory;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SchoolFeeCategoryService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        try {
            $schoolFeeCategory = SchoolFeeCategory::find($id);
            $schoolFeeCategory->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SchoolFeeCategoryService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
