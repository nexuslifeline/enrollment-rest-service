<?php

namespace App\Services;

use App\BankAccount;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BankAccountService
{
    public function list(bool $isPaginated, int $perPage)
    {
        try {
            $bankAccounts = $isPaginated
                ? BankAccount::paginate($perPage)
                : BankAccount::all();
            return $bankAccounts;
        } catch (Exception $e) {
            Log::info('Error occured during BankAccountService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $bankAccount = BankAccount::create($data);
            DB::commit();
            return $bankAccount;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during BankAccountService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $bankAccount = BankAccount::find($id);
            return $bankAccount;
        } catch (Exception $e) {
            Log::info('Error occured during BankAccountService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $bankAccount = BankAccount::find($id);
            $bankAccount->update($data);
            DB::commit();
            return $bankAccount;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during BankAccountService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        try {
            $bankAccount = BankAccount::find($id);
            $bankAccount->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during BankAccountService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}