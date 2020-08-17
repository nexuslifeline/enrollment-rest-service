<?php

namespace App\Services;

use App\SchoolFee;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchoolFeeService
{
    public function index(object $request)
    {
        try {
            $perPage = $request->per_page ?? 20;
            $schoolFees = !$request->has('paginate') || $request->paginate === 'true'
                ? SchoolFee::paginate($perPage)
                : SchoolFee::all();
            $schoolFees->load(['schoolFeeCategory']);
            return $schoolFees;
        } catch (Exception $e) {
            Log::info('Error occured during SchoolFeeService index method call: ');
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

    public function update(array $data, SchoolFee $schoolFee)
    {
        DB::beginTransaction();
        try {
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

    public function delete(SchoolFee $schoolFee)
    {
        try {
            $schoolFee->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SchoolFeeService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        } 
    }
}
