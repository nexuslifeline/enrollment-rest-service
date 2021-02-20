<?php

namespace App\Services;

use App\StudentGrade;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentGradeService
{
  public function list(bool $isPaginated, int $perPage, array $filters)
  {
    try {
      $query = StudentGrade::with('student', 'details');
      $sectionId = $filters['section_id'] ?? false;

      $query->when($sectionId, function($q) use($sectionId) {
        return $q->where('section_id', $sectionId);
      });
      $subjectId = $filters['subject_id'] ?? false;
      $query->when($subjectId, function ($q) use ($subjectId) {
        return $q->where('subject_id', $subjectId);
      });
      $criteria = $filters['criteria'] ?? false;
      $query->when($criteria, function ($q) use ($criteria) {
        return $q->where(function($q) use ($criteria) {
          return $q->whereHas('student', function($q) use ($criteria) {
            return $q->where('first_name', 'LIKE', '%'.$criteria.'%')
              ->orWhere('middle_name', 'LIKE', '%'.$criteria.'%')
              ->orWhere('last_name', 'LIKE', '%' . $criteria . '%')
              ->orWhere('student_no', 'LIKE', '%' . $criteria . '%');
            });
          });
      });


      $studentGrades = $isPaginated
        ? $query->paginate($perPage)
        : $query->get();

      return $studentGrades;
    } catch (Exception $e) {
      Log::info('Error occured during StudentGradeService list method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  public function store(array $data, array $details)
  {
    DB::beginTransaction();
    try {
      $studentGrade = StudentGrade::create($data);
      if ($details) {
        $items = [];
        foreach ($details as $detail) {
          $items[$detail['term_id']] = [
            'personnel_id' => $detail['personnel_id'],
            'grade' => $detail['grade'],
            // 'notes' => $detail['notes']
          ];
        }
        $studentGrade->details()->sync($items);
      }
      
      DB::commit();
      return $studentGrade;
    } catch (Exception $e) {
      DB::rollback();
      Log::info('Error occured during TermService store method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  public function update(array $data, array $details, int $id)
  {
    DB::beginTransaction();
    try {
      $studentGrade = StudentGrade::find($id);
      $studentGrade->update($data);
      if ($details) {
        $items = [];
        foreach ($details as $detail) {
          $items[$detail['term_id']] = [
            'personnel_id' => $detail['personnel_id'],
            'grade' => $detail['grade'],
            // 'notes' => $detail['notes']
          ];
        }
        $studentGrade->details()->sync($items);
      }
      DB::commit();
      return $studentGrade;
    } catch (Exception $e) {
      DB::rollback();
      Log::info('Error occured during TermService update method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  public function batchUpdate(array $data)
  {
    DB::beginTransaction();
    try {
      $result = [];
      foreach ($data as $value) {
        $studentGrade = StudentGrade::find($value['id']);
        $studentGrade->update([
          'student_id' => $value['student_id'],
          'section_id' => $value['section_id'],
          'subject_id' => $value['subject_id'],
        ]);
        if ($value['details']) {
          $items = [];
          foreach ($value['details'] as $detail) {
            $items[$detail['term_id']] = [
              'personnel_id' => $detail['personnel_id'],
              'grade' => $detail['grade'],
              // 'notes' => $detail['notes']
            ];
          }
          $studentGrade->details()->sync($items);
        }
        $result[] = $studentGrade;
      }
      DB::commit();
      return $result;
    } catch (Exception $e) {
      DB::rollback();
      Log::info('Error occured during TermService batchUpdate method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }
}