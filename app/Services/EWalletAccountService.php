<?php

namespace App\Services;

use App\EWalletAccount;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EWalletAccountService
{
    public function list(bool $isPaginated, int $perPage)
    {
        try {
            $eWalletAccounts = $isPaginated
                ? EWalletAccount::paginate($perPage)
                : EWalletAccount::all();
            return $eWalletAccounts;
        } catch (Exception $e) {
            Log::info('Error occured during EWalletAccountService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $eWalletAccount = EWalletAccount::create($data);
            DB::commit();
            return $eWalletAccount;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during EWalletAccountService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $eWalletAccount = EWalletAccount::find($id);
            return $eWalletAccount;
        } catch (Exception $e) {
            Log::info('Error occured during EWalletAccountService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $eWalletAccount = EWalletAccount::find($id);
            $eWalletAccount->update($data);
            DB::commit();
            return $eWalletAccount;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during EWalletAccountService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        try {
            $eWalletAccount = EWalletAccount::find($id);
            $eWalletAccount->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during EWalletAccountService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}