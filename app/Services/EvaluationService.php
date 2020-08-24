<?php

namespace App\Services;

use App\Evaluation;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class EvaluationService
{
    public function list(bool $isPaginated, int $perPage, array $filters)
    {
        try {
            $query = Evaluation::with([
              'lastSchoolLevel',
              'level', 
              'course', 
              'studentCategory',
              'curriculum',
              'studentCurriculum',
              'student' => function($query) {
                  $query->with(['address', 'photo']);
              }])
              ->withCount('files')
              ->where('evaluation_status_id', '!=', 1);

            // filters
            // student
            $studentId = $filters['student_id'] ?? false;
            $query->when($studentId, function($q) use ($studentId) {
                return $q->whereHas('student', function($query) use ($studentId) {
                    return $query->where('student_id', $studentId);
                });
            });

            //school category
            $schoolCategoryId = $filters['school_category_id'] ?? false;
            $query->when($schoolCategoryId, function($q) use ($schoolCategoryId) {
                return $q->where('school_category_id', $schoolCategoryId);
            });

            // course
            $courseId = $filters['course_id'] ?? false;
            $query->when($courseId, function($q) use ($courseId) {
                return $q->whereHas('course', function($query) use ($courseId) {
                    return $query->where('course_id', $courseId);
                });
            });
            
            // level
            $levelId = $filters['level_id'] ?? false;
            $query->when($levelId, function($q) use ($levelId) {
                return $q->whereHas('level', function($query) use ($levelId) {
                    return $query->where('level_id', $levelId);
                });
            });

            // evaluation status
            $evaluationStatusId = $filters['evaluation_status_id'] ?? false;
            $query->when($evaluationStatusId, function($query) use ($evaluationStatusId) {
                return $query->where('evaluation_status_id', $evaluationStatusId);
            });

            // filter by student name
            $criteria = $filters['criteria'] ?? false;
            $query->when($criteria, function($q) use ($criteria) {
                return $q->whereHas('student', function($query) use ($criteria) {
                    return $query->where(function($q) use ($criteria) {
                        return $q->where('name', 'like', '%'.$criteria.'%')
                            ->orWhere('first_name', 'like', '%'.$criteria.'%')
                            ->orWhere('middle_name', 'like', '%'.$criteria.'%')
                            ->orWhere('last_name', 'like', '%'.$criteria.'%');
                    });
                });
            });
        
            $evaluations = $isPaginated
            ? $query->paginate($perPage)
            : $query->get();
            return $evaluations;
        } catch (Exception $e) {
          Log::info('Error occured during EvaluationService list method call: ');
          Log::info($e->getMessage());
          throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $evaluation = Evaluation::find($id);
            $evaluation->load('subjects');
            return $evaluation;
        } catch (Exception $e) {
            Log::info('Error occured during EvaluationService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, array $subjects, int $id)
    {
        DB::beginTransaction();
        try {
            $evaluation = Evaluation::find($id);
            $evaluation->update($data);

            if ($subjects) {
                $items = [];
                foreach ($subjects as $subject) {
                    $items[$subject['subject_id']] = [
                        'level_id' => $subject['level_id'],
                        'semester_id' => $subject['semester_id'],
                        'is_taken' => $subject['is_taken'],
                        'grade' => $subject['grade'],
                        'notes' => $subject['notes']
                    ];
                }
                $evaluation->subjects()->sync($items);
            }

            $evaluation->load([
                'lastSchoolLevel',
                'level', 
                'course', 
                'studentCategory',
                'student' => function($query) {
                    $query->with(['address', 'photo']);
            }])->fresh();
            DB::commit();
            return $evaluation;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during EvaluationService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}