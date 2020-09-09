<?php

namespace App\Services;

use App\PermissionGroup;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermissionGroupService
{
    public function list(bool $isPaginated, int $perPage)
    {
        try {
            $permissions = $isPaginated
                ? PermissionGroup::paginate($perPage)
                : PermissionGroup::get();
            $permissions->load('permissions');
            return $permissions;
        } catch (Exception $e) {
            Log::info('Error occured during PermissionGroupService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}