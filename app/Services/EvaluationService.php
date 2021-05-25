<?php

namespace App\Services;

use App\Evaluation;
use App\Student;
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
                // 'level',
                // 'course',
                // 'studentCategory',
                //'curriculum',
                // 'studentCurriculum',
                // 'transcriptRecord',
                'academicRecord' => function ($query) {
                    $query->with(['curriculum', 'schoolYear', 'level', 'course', 'studentCategory']);
                },
                'student' => function ($query) {
                    $query->with(['address', 'photo', 'user']);
                }
            ])
            ->where('evaluation_status_id', '!=', 1);

            // filters
            // student
            $studentId = $filters['student_id'] ?? false;
            $query->when($studentId, function ($q) use ($studentId) {
                return $q->whereHas('student', function ($query) use ($studentId) {
                    $query->where('student_id', $studentId);
                });
            });

            // school year
            $schoolYearId = $filters['school_year_id'] ?? false;
            $query->when($schoolYearId, function ($q) use ($schoolYearId) {
                return $q->whereHas('academicRecord', function ($query) use ($schoolYearId) {
                    $query->where('school_year_id', $schoolYearId);
                });
                //return $q->where('school_year_id', $schoolYearId);
            });

            //school category
            // $schoolCategoryId = $filters['school_category_id'] ?? false;
            // $query->when($schoolCategoryId, function ($q) use ($schoolCategoryId) {
            //     return $q->whereHas('academicRecord', function ($query) use ($schoolCategoryId) {
            //         $query->where('school_category_id', $schoolCategoryId);
            //     });
            // });

            // course
            $courseId = $filters['course_id'] ?? false;
            $query->when($courseId, function ($q) use ($courseId) {
                return $q->whereHas('course', function ($query) use ($courseId) {
                    return $query->where('course_id', $courseId);
                });
            });

            // level
            $levelId = $filters['level_id'] ?? false;
            $query->when($levelId, function ($q) use ($levelId) {
                return $q->whereHas('level', function ($query) use ($levelId) {
                    return $query->where('level_id', $levelId);
                });
            });

            // evaluation status
            $evaluationStatusId = $filters['evaluation_status_id'] ?? false;
            $query->when($evaluationStatusId, function ($query) use ($evaluationStatusId) {
                return $query->where('evaluation_status_id', $evaluationStatusId);
            });

            // filter by student name
            $criteria = $filters['criteria'] ?? false;
            $query->when($criteria, function ($q) use ($criteria) {
                return $q->whereHas('student', function ($query) use ($criteria) {
                    //scopedWhereLike on student model
                    return  $query->whereLike($criteria);
                });
            });

            // order by
            $orderBy = $filters['order_by'] ?? false;
            $query->when($orderBy, function ($q) use ($orderBy, $filters) {
                $sort = $filters['sort'] ?? 'ASC';
                return $q->orderBy($orderBy, $sort);
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
            $evaluation->load([
                // 'subjects' => function ($query) {
                //     $query->with('prerequisites');
                // },
                //'studentCategory',
                //'course',
                //'level',
                'academicRecord' => function ($query) {
                    $query->with(['curriculum', 'schoolYear', 'level', 'course', 'studentCategory', 'transcriptRecord']);
                },
                // 'transcriptRecord', //disabled for adjustment on transcript record 5/15/2021
                'student' => function ($query) {
                    $query->with(['address', 'photo', 'user', 'education']);
                }
            ]);
            return $evaluation;
        } catch (Exception $e) {
            Log::info('Error occured during EvaluationService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, array $subjects, array $transcriptData, int $id)
    {
        DB::beginTransaction();
        try {
            $evaluation = Evaluation::find($id);
            $evaluation->update($data);

            //disabled for adjustment on transcript record 5/15/2021
            // if (count($transcriptData) > 0) {
            //     $transcriptRecord = $evaluation->transcriptRecord;
            //     $transcriptRecord->update($transcriptData);
            //     if ($subjects) {
            //         $items = [];
            //         foreach ($subjects as $subject) {
            //             $items[$subject['subject_id']] = [
            //                 'level_id' => $subject['level_id'],
            //                 'semester_id' => $subject['semester_id'],
            //                 'is_taken' => $subject['is_taken'],
            //                 'grade' => $subject['grade'],
            //                 'notes' => $subject['notes']
            //             ];
            //         }
            //         $transcriptRecord->subjects()->sync($items);
            //     }
            // }

            $evaluation->load([
                'lastSchoolLevel',
                'level',
                'course',
                'studentCategory',
                'student' => function ($query) {
                    $query->with(['address', 'photo']);
                }
            ])->fresh();
            DB::commit();
            return $evaluation;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during EvaluationService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getEvaluationsOfStudent(int $studentId, bool $isPaginated, int $perPage, array $filters)
    {
        try {
            $query = Student::find($studentId)->evaluations();
            // evaluation status
            $evaluationStatusId = $filters['evaluation_status_id'] ?? false;
            $query->when($evaluationStatusId, function ($query) use ($evaluationStatusId) {
                return $query->where('evaluation_status_id', $evaluationStatusId);
            });
            $evaluations = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();
            $evaluations->load([
                'lastSchoolLevel',
                'level',
                'course',
                'studentCategory',
                'curriculum',
                'studentCurriculum',
                'student' => function ($query) {
                    $query->with(['address', 'photo']);
                }
            ]);
            return $evaluations;
        } catch (Exception $e) {
            Log::info('Error occured during EvaluationService getEvaluationOfStudent method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
