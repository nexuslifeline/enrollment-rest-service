<?php

namespace App\Services;

use App\Permission;
use App\UserGroup;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermissionService
{
    public function getPermissionsOfUserGroup(int $userGroupId, bool $isPaginated, int $perPage)
    {
        try {
          $userGroup = UserGroup::find($userGroupId);
          $query = $userGroup->permissions();
          $permissions = $isPaginated
            ? $query->paginate($perPage)
            : $query->get();
          return $permissions;
        } catch (Exception $e) {
          Log::info('Error occured during PermissionService getPermissionOfUserGroup method call: ');
          Log::info($e->getMessage());
          throw $e;
        }
    }

    public function storePermissionsOfUserGroup(int $userGroupId, array $data)
    {
      try {
        $userGroup = UserGroup::find($userGroupId);
        $permissions = $userGroup->permissions();
        $permissions->sync($data);
        return $permissions->get();
      } catch (Exception $e) {
        Log::info('Error occured during PermissionService storePermissionsOfUserGroup method call: ');
        Log::info($e->getMessage());
        throw $e;
      }
    }
}