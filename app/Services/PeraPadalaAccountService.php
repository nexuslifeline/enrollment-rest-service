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
}