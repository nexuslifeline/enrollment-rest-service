<?php

namespace App\Services;

use App\StudentClearance;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentClearanceService
{
  public function list(bool $isPaginated, int $perPage, array $filters)
  {
    try {
      $query = StudentClearance::with([
        'student', 'academicRecord'
      ]);
      // filters
      // student
      $studentId = $filters['student_id'] ?? false;
      $query->when($studentId, function ($q) use ($studentId) {
        return $q->whereHas('student', function ($query) use ($studentId) {
          return $query->where('student_id', $studentId);
        });
      });
      // school year
      $schoolYearId = $filters['school_year_id'] ?? false;
      $query->when($schoolYearId, function ($q) use ($schoolYearId) {
        return $q->whereHas('academicRecord', function ($query) use ($schoolYearId) {
          return $query->where('school_year_id', $schoolYearId);
        });
      });
      // semester
      $semesterId = $filters['semester_id'] ?? false;
      $query->when($semesterId, function ($q) use ($semesterId) {
        return $q->whereHas('academicRecord', function ($query) use ($semesterId) {
          return $query->where('semester_id', $semesterId);
        });
      });

      // course
      $courseId = $filters['course_id'] ?? false;
      $query->when($courseId, function ($q) use ($courseId) {
        return $q->whereHas('academicRecord', function ($q) use ($courseId) {
          return $q->where('course_id', $courseId);
        });
      });

      // level
      $levelId = $filters['level_id'] ?? false;
      $query->when($levelId, function ($q) use ($levelId) {
        return $q->whereHas('academicRecord', function ($q) use ($levelId) {
          return $q->where('level_id', $levelId);
        });
      });

      // school category
      $schoolCategoryId = $filters['school_category_id'] ?? false;
      $query->when($schoolCategoryId, function ($q) use ($schoolCategoryId) {
        return $q->whereHas('academicRecord', function ($q) use ($schoolCategoryId) {
          return $q->where('school_category_id', $schoolCategoryId);
        });
      });
      
      // filter by student name
      $criteria = $filters['criteria'] ?? false;
      $query->when($criteria, function ($q) use ($criteria) {
        return $q->whereHas('student', function ($query) use ($criteria) {
          return $query->where('name', 'like', '%' . $criteria . '%')
            ->orWhere('student_no', 'like', '%' . $criteria . '%')
            ->orWhere('first_name', 'like', '%' . $criteria . '%')
            ->orWhere('middle_name', 'like', '%' . $criteria . '%')
            ->orWhere('last_name', 'like', '%' . $criteria . '%');
        });
      });

      $orderBy = $filters['order_by'] ?? false;
      $query->when($orderBy, function ($q) use ($orderBy, $filters) {
        $sort = $filters['sort'] ?? 'ASC';
        return $q->orderBy($orderBy, $sort);
      });

      $studentClearances = $isPaginated
        ? $query->paginate($perPage)
        : $query->get();
      return $studentClearances;
    } catch (Exception $e) {
      Log::info('Error occured during StudentClearanceService list method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  public function get(int $id)
  {
    try {
      $studentClearance = StudentClearance::find($id);
      $studentClearance->load([
        'student', 'academicRecord','signatories'
      ]);
      return $studentClearance;
    } catch (Exception $e) {
      Log::info('Error occured during StudentClearanceService get method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  public function batchStore(array $data, array $signatories)
  {
    DB::beginTransaction();
    try {
      
      DB::commit();
      // return $billings;
    } catch (Exception $e) {
      DB::rollback();
      Log::info('Error occured during StudentClearanceService storeBatchClearance method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  public function store(array $data, array $signatories)
  {
    DB::beginTransaction();
    try {
      $studentClearance = StudentClearance::create($data);
      DB::commit();
      return $studentClearance;
    } catch (Exception $e) {
      DB::rollback();
      Log::info('Error occured during StudentClearanceService store method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  public function update(int $id, array $data, array $signatories)
  {
    DB::beginTransaction();
    try {

      $studentClearance = StudentClearance::find($id);

      $studentClearance->update($data);
      DB::commit();
      return $studentClearance;
    } catch (Exception $e) {
      DB::rollback();
      Log::info('Error occured during StudentClearanceService update method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  public function delete(int $id)
  {
    try {
      DB::beginTransaction();
      $studentClearance = StudentClearance::find($id);
      // if billing is soa update student_fee_term is_billed to 0 so you can create it again
      $studentClearance->delete();
      DB::commit();
    } catch (Exception $e) {
      DB::rollback();
      Log::info('Error occured during StudentClearanceService delete method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }
}
