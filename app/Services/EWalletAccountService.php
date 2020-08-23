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
}