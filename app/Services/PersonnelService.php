<?php

namespace App\Services;

use App\Personnel;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class PersonnelService
{
    public function list(bool $isPaginated, int $perPage, array $filter)
    {
        try {
            $query = Personnel::with(['photo','department', 'user' => function ($query) {
              $query->with('userGroup');
            }]);

            $userGroupId = $filter['user_group_id'] ?? false;
            $query->when($userGroupId, function($q) use ($userGroupId) {
              return $q->whereHas('user', function($query) use ($userGroupId) {
                return $query->whereHas('userGroup', function($q) use ($userGroupId) {
                  return $q->where('user_group_id', $userGroupId);
                });
              });
            });

            $departmentId = $filter['department_id'] ?? false;
            $query->when($departmentId, function($q) use ($departmentId) {
                return $q->where('department_id', $departmentId);
            });

            $personnels = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();
            return $personnels;
        } catch (Exception $e) {
            Log::info('Error occured during PersonnelService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $personnel = Personnel::find($id);
            $personnel->load(['photo','user' => function($query) {
                $query->with('userGroup');
            }]);
            return $personnel;
        } catch (Exception $e) {
            Log::info('Error occured during PersonnelService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(array $data, array $user)
    {
        DB::beginTransaction();
        try {
            $personnel = Personnel::create($data);

            $personnel->user()->create([
                'username' => $user['username'],
                'user_group_id' => $user['user_group_id'],
                'password' => Hash::make($user['password'])
            ]);

            $personnel->load(['photo','user' => function($query) {
                $query->with('userGroup');
            }]);
            DB::commit();
            return $personnel;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during PersonnelService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, array $user, int $id)
    {
        DB::beginTransaction();
        try {
            $personnel = Personnel::find($id);
            $personnel->update($data);

            if ($user) {

                $password = $user['password'] ?? false;
                $username = $user['username'] ?? false;
                if($username && $password) {
                    $personnel->user()->update([
                        'username' => $user['username'],
                        'user_group_id' => $user['user_group_id'],
                        'password' => Hash::make($user['password'])
                    ]);
                }
                elseif($username && !$password) {
                    $personnel->user()->update([
                        'username' => $user['username'],
                        'user_group_id' => $user['user_group_id']
                    ]);
                }
                else {
                    $personnel->user()->update([
                        'password' => Hash::make($user['password'])
                    ]);
                }
            }

            $personnel->load(['photo','user' => function($query) {
                $query->with('userGroup');
            }]);
            DB::commit();
            return $personnel;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during PersonnelService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        try {
            $personnel = Personnel::find($id);
            $personnel->delete();
            $personnel->user()->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during PersonnelService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
