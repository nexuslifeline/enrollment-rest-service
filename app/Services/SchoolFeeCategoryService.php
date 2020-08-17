<?php

namespace App\Services;

use App\SchoolFeeCategory;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchoolFeeCategoryService
{
    public function index(object $request)
    {
        try {
            $perPage = $request->per_page ?? 20;
            $schoolFeeCategories = !$request->has('paginate') || $request->paginate === 'true'
                ? SchoolFeeCategory::paginate($perPage)
                : SchoolFeeCategory::all();
            return $schoolFeeCategories;
        } catch (Exception $e) {
            Log::info('Error occured during SchoolFeeCategoryService index method call: ');
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

    public function update(array $data, SchoolFeeCategory $schoolFeeCategory)
    {
        DB::beginTransaction();
        try {
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

    public function delete(SchoolFeeCategory $schoolFeeCategory)
    {
        try {
            $schoolFeeCategory->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SchoolFeeCategoryService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        } 
    }
}
