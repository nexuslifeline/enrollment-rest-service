<?php

namespace App\Services;

use App\StudentGrade;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentGradeService
{
  public function list(bool $isPaginated, int $perPage, array $filters)
  {
    try {
      $query = StudentGrade::with('grades')
      ->filters($filters);
      

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

  public function batchUpdate(array $studentGrades)
  {
    DB::beginTransaction();
    try {
      $data = [];
      foreach ($studentGrades as $studentGrade) {
        $studentGradeData = StudentGrade::updateOrCreate(['id' => $studentGrade['student_grade_id']],[
          'student_id' => $studentGrade['student_id'],
          'school_year_id' => $studentGrade['school_year_id'],
          'course_id' => $studentGrade['course_id'],
          'level_id' => $studentGrade['level_id'],
          'semester_id' => $studentGrade['semester_id'],
          'section_id' => $studentGrade['section_id'],
          'subject_id' => $studentGrade['subject_id'],
          'personnel_id' => Auth::user()->userable->id,
          'notes' => $studentGrade['notes'],
          'student_grade_status_id' => $studentGrade['student_grade_status_id'],
        ]);
        $item = [];
        foreach ($studentGrade['grades'] as $grade) {
          $item[$grade['grading_period_id']] = [
            'grade' => $grade['grade']
          ];
        }

        $studentGradeData->grades()->sync($item);
      }
      DB::commit();
      return $data;
    } catch (Exception $e) {
      DB::rollback();
      Log::info('Error occured during TermService batchUpdate method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }
}