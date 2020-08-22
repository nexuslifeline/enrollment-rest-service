<?php

namespace App\Services;

use App\UserGroup;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserGroupService
{
    public function list(bool $isPaginated, int $perPage)
    {
        try {
            $userGroups = $isPaginated
                ? UserGroup::paginate($perPage)
                : UserGroup::all();
            return $userGroups;
        } catch (Exception $e) {
            Log::info('Error occured during UserGroupService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $userGroup = UserGroup::find($id);
            return $userGroup;
        } catch (Exception $e) {
            Log::info('Error occured during UserGroupService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $userGroup = UserGroup::create($data);  
            DB::commit();
            return $userGroup;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during UserGroupService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $userGroup = UserGroup::find($id);
            $userGroup->update($data); 
            DB::commit();
            return $userGroup;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during UserGroupService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        try {
            $userGroup = UserGroup::find($id);
            $userGroup->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during UserGroupService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        } 
    }
}
