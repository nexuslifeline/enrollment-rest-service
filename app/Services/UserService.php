<?php

namespace App\Services;

use App\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

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
            $user = User::find($id);
            return $user;
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
            $data['password'] = Hash::make($data['password']);

            $user = User::create($data);
            DB::commit();

            if ($user->userable_type === 'App\\Personnel') {
                $user->load(['userGroup']);
            }
            return $user;
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
            if (array_key_exists('password', $data)) {
                $data['password'] = Hash::make($data['password']);
            }

            $user = User::find($id);
            $user->update($data);
            $user->load(['userable', 'userable.photo']);
            if ($user->userable_type === 'App\\Student') {
                $user->userable->load('academicRecords');
                $user->userable->append([
                    // 'active_application',
                    'latest_academic_record',
                    // 'active_transcript_record',
                ]);
            } else {
                $user->load(['userGroup' => function ($q) {
                    return $q->select(['id', 'name'])->with(['permissions' => function ($q) {
                    return $q->select(['permissions.id', 'permission_group_id']);
                    }, 'schoolCategories']);
                }]);
            }

            DB::commit();
            return $user;
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
            $user = User::find($id);
            $user->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during UserService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
