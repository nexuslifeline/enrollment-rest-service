<?php

namespace App\Services;

use App\Personnel;
use Exception;
use Illuminate\Support\Facades\Auth;
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

            $studentGradesStatusId = $filter['student_grade_status_id'] ?? false;
            //check if personnel has student grades
            $query->when($studentGradesStatusId, function ($q) use ($studentGradesStatusId) {
                return $q->studentGrades($studentGradesStatusId);
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
            $personnel = Personnel::find($id)->makeVisible(['created_at', 'updated_at']);
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

            if (array_key_exists('user', $data)) {
                $personnel->user()->create([
                    'username' => $user['username'],
                    'user_group_id' => $user['user_group_id'],
                    'password' => Hash::make($user['password'])
                ]);
            }

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
                $userGroupId = $user['user_group_id'] ?? false;
                if($username && $password && $userGroupId) {
                    //username, usergroup and password
                    $personnel->user()->update([
                        'username' => $user['username'],
                        'user_group_id' => $user['user_group_id'],
                        'password' => Hash::make($user['password'])
                    ]);
                }
                elseif($username && $userGroupId &&!$password) {
                    //username and usergroup
                    $personnel->user()->update([
                        'username' => $user['username'],
                        'user_group_id' => $user['user_group_id']
                    ]);
                }
                elseif($username && !$userGroupId &&!$password) {
                    //username only
                    $personnel->user()->update([
                        'username' => $user['username'],
                    ]);
                }
                else {
                    //password only
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

    public function getEducationList(int $id, $isPaginated, $perPage)
    {
        try {
            $personnel = Personnel::find($id);
            $query = $personnel->education();
            $education = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();
            return $education;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during PersonnelService getEducationList method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function storeEducation(int $id, array $data)
    {
        try {
            $personnel = Personnel::find($id);
            $education = $personnel->education()->create($data);
            return $education;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during PersonnelService addEducation method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function updateEducation(int $id, int $educationId, array $data)
    {
        try {
            $personnel = Personnel::find($id);
            $education = $personnel->education()->find($educationId);
            $education->update($data);
            return $education;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during PersonnelService updateEducation method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function deleteEducation(int $id, int $educationId)
    {
        try {
            $personnel = Personnel::find($id);
            $education = $personnel->education()->find($educationId);
            $education->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during PersonnelService updateEducation method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getEmploymentList(int $id, $isPaginated, $perPage)
    {
        try {
            $personnel = Personnel::find($id);
            $query = $personnel->employments();
            $employments = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();
            return $employments;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during PersonnelService getEmploymentList method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function storeEmployment(int $id, array $data)
    {
        try {
            $personnel = Personnel::find($id);
            $employment = $personnel->employments()->create($data);
            return $employment;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during PersonnelService storeEmployment method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function updateEmployment(int $id, int $employmentId, array $data)
    {
        try {
            $personnel = Personnel::find($id);
            $employment = $personnel->employments()->find($employmentId);
            $employment->update($data);
            return $employment;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during PersonnelService updateEmployment method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
