<?php

namespace App\Services;

use App\UserGroup;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserGroupService
{
    public function index(object $request)
    {
        try {
            $perPage = $request->per_page ?? 20;
            $userGroups = !$request->has('paginate') || $request->paginate === 'true'
                ? UserGroup::paginate($perPage)
                : UserGroup::all();
            return $userGroups;
        } catch (Exception $e) {
            Log::info('Error occured during UserGroupService index method call: ');
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

    public function update(array $data, UserGroup $userGroup)
    {
        DB::beginTransaction();
        try {
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

    public function delete(UserGroup $userGroup)
    {
        try {
            $userGroup->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during UserGroupService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        } 
    }
}
