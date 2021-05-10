<?php

namespace App\Services;

use App\StudentGrade;
use App\TranscriptRecord;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentGradeService
{
  public function list(bool $isPaginated, int $perPage, array $filters)
  {
    try {
      $query = StudentGrade::with(['grades','personnel','subject','student','schoolYear', 'section' => function ($q) {
        return $q->with('schoolCategory');
      }])
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

  public function studentGradePersonnels(bool $isPaginated, int $perPage, array $filters)
  {
    try {
      $query = StudentGrade::select('personnel_id', 'section_id', 'subject_id', 'school_year_id', 'submitted_date')
        ->with(['section' => function ($q) {
          return $q->with('schoolCategory','schoolYear','level');
        },'personnel','subject'])
        ->groupBy('personnel_id', 'section_id','subject_id', 'school_year_id', 'submitted_date')
        ->where('student_grade_status_id', 2);

      $studentGrades = $isPaginated
        ? $query->paginate($perPage)
        : $query->get();

      return $studentGrades;
    } catch (Exception $e) {
      Log::info('Error occured during StudentGradeService studentGradePersonnels method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  public function acceptStudentGrade(int $personnelId, int $sectionId, int $subjectId)
  {
    try {
      $studentGrades = StudentGrade::with(['section','grades'])
        ->where('personnel_id', $personnelId)
        ->where('section_id', $sectionId)
        ->where('subject_id', $subjectId)
        ->get();

      foreach ($studentGrades as $studentGrade) {
        $transcriptRecord = TranscriptRecord::where('student_id', $studentGrade->student_id)
          ->where('school_category_id', $studentGrade->section->school_category_id)
          ->when(!in_array($studentGrade->section->school_category_id, [4, 5, 6]), function ($q) use ($studentGrade) {
            return $q->where('level_id', $studentGrade->level_id);
          })
          ->where('course_id', $studentGrade->course_id)
          ->where('transcript_record_status_id', 1)
          ->first();
        $subject = $transcriptRecord->subjects()
          ->where('id', $subjectId)
          ->where('level_id', $studentGrade->level_id)
          ->where('semester_id', $studentGrade->semester_id);
        $grade = $studentGrade->grades
          ->avg('pivot.grade');
        $subject->updateExistingPivot($subjectId, [
          'grade' => $grade
        ]);
        $studentGrade->update([
          'student_grade_status_id' => 3
        ]);
      }
      return $studentGrades;
    } catch (Exception $e) {
      Log::info('Error occured during StudentGradeService acceptStudentGrade method call: ');
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
          'submitted_date' => $studentGrade['student_grade_status_id'] == 2 ? Carbon::now() : null
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