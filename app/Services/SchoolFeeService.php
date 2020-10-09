<?php

namespace App\Services;

use App\SchoolFee;
use App\Term;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchoolFeeService
{
    public function list(bool $isPaginated, int $perPage)
    {
        try {
            $schoolFees = $isPaginated
                ? SchoolFee::paginate($perPage)
                : SchoolFee::all();
            $schoolFees->load(['schoolFeeCategory']);
            return $schoolFees;
        } catch (Exception $e) {
            Log::info('Error occured during SchoolFeeService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $schoolFee = SchoolFee::find($id);
            $schoolFee->load(['schoolFeeCategory']);
            return $schoolFee;
        } catch (Exception $e) {
            Log::info('Error occured during SchoolFeeCategoryService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $schoolFee = SchoolFee::create($data);
            $schoolFee->load(['schoolFeeCategory']);
            DB::commit();
            return $schoolFee;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SchoolFeeService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $schoolFee = SchoolFee::find($id);
            $schoolFee->update($data);
            $schoolFee->load(['schoolFeeCategory']);
            DB::commit();
            return $schoolFee;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SchoolFeeService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        try {
            $schoolFee = SchoolFee::find($id);
            $schoolFee->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SchoolFeeService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
