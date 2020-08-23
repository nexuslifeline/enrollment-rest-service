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
}