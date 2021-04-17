<?php

namespace App\Services;

use App\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function list(bool $isPaginated, int $perPage)
    {
        try {
            $users = $isPaginated
                ? User::paginate($perPage)
                : User::all();
            return $users;
        } catch (Exception $e) {
            Log::info('Error occured during UserService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $User = User::find($id);
            return $User;
        } catch (Exception $e) {
            Log::info('Error occured during UserService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $User = User::create($data);
            DB::commit();
            return $User;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during UserService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $User = User::find($id);
            $User->update($data);
            DB::commit();
            return $User;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during UserService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        try {
            $User = User::find($id);
            $User->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during UserService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
