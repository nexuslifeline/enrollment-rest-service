<?php

namespace App\Services;

use App\Requirement;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RequirementService
{
  public function list(bool $isPaginated, int $perPage, array $filters)
  {
    try {
      $query = Requirement::with(['schoolCategory']);

      //filter by school category
      $schoolCategoryId = $filters['school_category_id'] ?? false;
      $query->when($schoolCategoryId, function ($q) use ($schoolCategoryId) {
        return $q->where('school_category_id', $schoolCategoryId);
      });

      $requirements = $isPaginated
        ? $query->paginate($perPage)
        : $query->get();

      return $requirements;
    } catch (Exception $e) {
      Log::info('Error occured during RequirementService list method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  public function updateCreateMultiple(int $schoolCategoryId, array $data)
  {
    DB::beginTransaction();
    try {
      $requirements = [];
      foreach ($data as $requirement) {
        $requirements[] = Requirement::updateOrCreate(
          ['id' => $requirement['id']],
          [
            'name' => $requirement['name'],
            'school_category_id' => $schoolCategoryId,
          ]
        );
      }

      DB::commit();
      return $requirements;
    } catch (Exception $e) {
      DB::rollback();
      Log::info('Error occured during RequirementService updateCreateMultiple method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  public function delete(int $id)
  {
    DB::beginTransaction();
    try {
      $requirement = Requirement::find($id);
      $requirement->delete();
      DB::commit();
    } catch (Exception $e) {
      DB::rollback();
      Log::info('Error occured during RequirementService delete method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }
}
