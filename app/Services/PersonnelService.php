<?php

namespace App\Services;

use App\Personnel;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class PersonnelService
{
    public function index(object $request)
    {
        try {
            $perPage = $request->per_page ?? 20;

            $query = Personnel::with(['user' => function ($query) {
              $query->with('userGroup');
            }]);
    
            $userGroupId = $request->user_group_id ?? false;
    
            $query->when($userGroupId, function($q) use ($userGroupId) {
              return $q->whereHas('user', function($query) use ($userGroupId) {
                return $query->whereHas('userGroup', function($q) use ($userGroupId) {
                  return $q->where('user_group_id', $userGroupId);
                });
              });
            });
    
            $personnels = !$request->has('paginate') || $request->paginate === 'true'
                ? $query->paginate($perPage)
                : $query->get();
            return $personnels;
        } catch (Exception $e) {
            Log::info('Error occured during PersonnelService index method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(object $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('user');

            $personnel = Personnel::create($data);
    
            $personnel->user()->create([
                'username' => $request->user['username'],
                'user_group_id' => $request->user['user_group_id'],
                'password' => Hash::make($request->user['password'])
            ]);
    
            $personnel->load(['user' => function($query) {
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

    public function update(object $request, Personnel $personnel)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('user');

            $personnel->update($data);
            
            if ($request->has('user')) {
                $personnel->user()->update([
                    'username' => $request->user['username'],
                    'user_group_id' => $request->user['user_group_id'],
                    'password' => Hash::make($request->user['password'])
                ]);
            }
            
            $personnel->load(['user' => function($query) {
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

    public function delete(Personnel $personnel)
    {
        try {
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
