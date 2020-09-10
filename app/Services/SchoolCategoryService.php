<?php

namespace App\Services;

use App\SchoolCategory;
use App\UserGroup;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchoolCategoryService
{
    public function list(bool $isPaginated, int $perPage)
    {
      try {
        $schoolCategories = $isPaginated
          ? SchoolCategory::paginate($perPage)
          : SchoolCategory::all();
        return $schoolCategories;
      } catch (Exception $e) {
          Log::info('Error occured during SchoolCategoryService list method call: ');
          Log::info($e->getMessage());
          throw $e;
      }
    }

    public function getSchoolCategoriesOfUserGroup(int $userGroupId, bool $isPaginated, int $perPage)
    {
        try {
          $userGroup = UserGroup::find($userGroupId);
          $query = $userGroup->schoolCategories();
          $schoolCategories = $isPaginated
            ? $query->paginate($perPage)
            : $query->get();
          return $schoolCategories;
        } catch (Exception $e) {
          Log::info('Error occured during SchoolCategoryService getSchoolCategoriesOfUserGroup method call: ');
          Log::info($e->getMessage());
          throw $e;
        }
    }

    public function storeSchoolCategoriesOfUserGroup(int $userGroupId, array $data)
    {
      try {
        $userGroup = UserGroup::find($userGroupId);
        $schoolCategories = $userGroup->schoolCategories();
        $schoolCategories->sync($data);
        return $schoolCategories->get();
      } catch (Exception $e) {
        Log::info('Error occured during SchoolCategoryService storeSchoolCategoriesOfUserGroup method call: ');
        Log::info($e->getMessage());
        throw $e;
      }
    }
}