<?php

namespace App\Services;

use App\Requirement;
use App\Student;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RequirementService
{
  public function list(bool $isPaginated, int $perPage, array $filters)
  {
    try {
      $query = Requirement::with(['schoolCategory', 'documentType']);

      //filter by school category
      $schoolCategoryId = $filters['school_category_id'] ?? false;
      $query->when($schoolCategoryId, function ($q) use ($schoolCategoryId) {
        return $q->where('school_category_id', $schoolCategoryId);
      });

      //filter by document type
      $documentTypeId = $filters['document_type_id'] ?? false;
      $query->when($documentTypeId, function ($q) use ($documentTypeId) {
        return $q->where('document_type_id', $documentTypeId);
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

  public function get(int $id)
  {
    try {
      $requirement = Requirement::find($id);
      $requirement->load(['schoolCategory', 'documentType']);
      return $requirement;
    } catch (Exception $e) {
      Log::info('Error occured during RequirementService get method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  public function store(array $data)
  {
    DB::beginTransaction();
    try {
      $requirement = Requirement::create($data);
      DB::commit();
      $requirement->load(['schoolCategory','documentType']);
      return $requirement;
    } catch (Exception $e) {
      DB::rollback();
      Log::info('Error occured during RequirementService store method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  public function update(array $data, int $id)
  {
    DB::beginTransaction();
    try {
      $requirement = Requirement::find($id);
      $requirement->update($data);
      DB::commit();
      $requirement->load(['schoolCategory', 'documentType']);
      return $requirement;
    } catch (Exception $e) {
      DB::rollback();
      Log::info('Error occured during RequirementService update method call: ');
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

  public function updateStudentRequirements(int $studentId, int $schoolCategoryId, int $requirementId, array $data)
  {
    DB::beginTransaction();
    try {
      $studentRequirement = Student::find($studentId)
        ->requirements();

      $requirement = $studentRequirement->wherePivot('school_category_id', $schoolCategoryId)
        ->where('requirement_id', $requirementId)
        ->first();
      if ($requirement) {
        $studentRequirement
        ->updateExistingPivot(
          $requirementId,
          [
            'school_category_id' => $schoolCategoryId,
            'is_submitted' => $data['is_submitted']
          ]
        );
      } else {
        $studentRequirement
        ->attach(
          $requirementId,
          [
            'school_category_id' => $schoolCategoryId,
            'is_submitted' => $data['is_submitted']
          ]
        );

        $requirement = $studentRequirement->wherePivot('school_category_id', $schoolCategoryId)
        ->where('requirement_id', $requirementId)
        ->first();
      }

      // $requirement = $studentRequirement->where('requirement_id', $requirementId)
      //   ->where('school_category_id', $schoolCategoryId)
      //   ->first()
      //   ->latest();
      DB::commit();
      return $requirement;
    } catch (Exception $e) {
      DB::rollback();
      Log::info('Error occured during updateStudentRequirements updateCreateMultiple method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  public function getStudentRequirements(int $studentId, int $schoolCategoryId)
  {
    try {
      $requirements = Requirement::with(['schoolCategory', 'documentType'])
        ->where('school_category_id', $schoolCategoryId)
        ->get();

      foreach ($requirements as $requirement) {
        $requirement->is_submitted = $this->isSubmitted(
          $studentId,
          $requirement->id
        );
      }

      $requirements->append(['is_submitted']);
      return $requirements;
    } catch (Exception $e) {
      Log::info('Error occured during getStudentRequirements list method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  private function isSubmitted(int $studentId, int $requirementId)
  {
    $student = Student::find($studentId);

    if ($student) {
      $requirement = $student->requirements()->find($requirementId);
      if ($requirement && $requirement->pivot->is_submitted) {
        return true;
      }
    }

    return false;
  }
}
