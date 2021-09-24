<?php

namespace App\Services;

use App\GradeSymbol;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GradeSymbolService
{
    public function list(bool $isPaginated, int $perPage)
    {
        try {
            $gradeSymbols = $isPaginated
                ? GradeSymbol::paginate($perPage)
                : GradeSymbol::all();
            return $gradeSymbols;
        } catch (Exception $e) {
            Log::info('Error occured during GradeSymbolService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $gradeSymbol = GradeSymbol::create($data);
            DB::commit();
            return $gradeSymbol;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during GradeSymbolService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $gradeSymbol = GradeSymbol::find($id);
            return $gradeSymbol;
        } catch (Exception $e) {
            Log::info('Error occured during GradeSymbolService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $gradeSymbol = GradeSymbol::find($id);
            $gradeSymbol->update($data);
            DB::commit();
            return $gradeSymbol;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during GradeSymbolService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        DB::beginTransaction();
        try {
            $gradeSymbol = GradeSymbol::find($id);
            $gradeSymbol->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during GradeSymbolService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}