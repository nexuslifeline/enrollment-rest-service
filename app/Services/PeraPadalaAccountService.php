<?php

namespace App\Services;

use App\PeraPadalaAccount;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PeraPadalaAccountService
{
    public function list(bool $isPaginated, int $perPage)
    {
        try {
            $peraPadalaAccounts = $isPaginated
                ? PeraPadalaAccount::paginate($perPage)
                : PeraPadalaAccount::all();
            return $peraPadalaAccounts;
        } catch (Exception $e) {
            Log::info('Error occured during PeraPadalaAccountService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $peraPadalaAccount = PeraPadalaAccount::create($data);
            DB::commit();
            return $peraPadalaAccount;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during PeraPadalaAccountService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $peraPadalaAccount = PeraPadalaAccount::find($id);
            return $peraPadalaAccount;
        } catch (Exception $e) {
            Log::info('Error occured during PeraPadalaAccountService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $peraPadalaAccount = PeraPadalaAccount::find($id);
            $peraPadalaAccount->update($data);
            DB::commit();
            return $peraPadalaAccount;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during PeraPadalaAccountService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        DB::beginTransaction();
        try {
            $peraPadalaAccount = PeraPadalaAccount::find($id);
            $peraPadalaAccount->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during PeraPadalaAccountService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}