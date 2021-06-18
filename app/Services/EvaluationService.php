<?php

namespace App\Services;

use App\Evaluation;
use App\Student;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
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
                'academicRecord' => function ($query) {
                    $query->with(['schoolYear', 'level', 'course', 'studentCategory']);
                },
                'student' => function ($query) {
                    $query->with(['address', 'photo', 'user']);
                }
            ]);

            // filters
            // student
            $studentId = $filters['student_id'] ?? false;
            $query->when($studentId, function ($q) use ($studentId) {
                return $q->whereHas('student', function ($query) use ($studentId) {
                    $query->where('student_id', $studentId);
                });
            });


             // academic record status, school year, school cateogry, level, course
             $academicStatusId = $filters['academic_record_status_id'] ?? false;
             $schoolYearId = $filters['school_year_id'] ?? false;
             $courseId = $filters['course_id'] ?? false;
             $schoolCategoryId = $filters['school_category_id'] ?? false;
             $levelId = $filters['level_id'] ?? false;

             $query->when(
                $academicStatusId || $schoolYearId || $courseId || $schoolCategoryId || $levelId,
                function ($q) use ($academicStatusId, $schoolYearId, $courseId, $schoolCategoryId, $levelId) {
                    return $q->whereHas(
                        'academicRecord',
                        function ($query) use ($academicStatusId, $schoolYearId, $courseId, $schoolCategoryId, $levelId) {
                            if ($academicStatusId) $query->whereIn('academic_record_status_id', $academicStatusId);
                            if ($schoolYearId) $query->where('school_year_id', $schoolYearId);
                            if ($courseId) $query->where('course_id', $courseId);
                            if ($schoolCategoryId) $query->where('school_category_id', $schoolCategoryId);
                            if ($levelId) $query->where('level_id', $levelId);
                            $query->where('is_manual', false);
                            return $query;
                        }
                    );
                }
            );

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
                'academicRecord' => function ($query) {
                    $query->with(['schoolYear', 'level', 'course', 'studentCategory', 'transcriptRecord' => function($q) {
                        return $q->with(['curriculum', 'studentCurriculum']);
                    }]);
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
                // 'level',
                // 'course',
                // 'studentCategory',
                'academicRecord' => function ($query) {
                    $query->with(['curriculum', 'schoolYear', 'level', 'course', 'studentCategory', 'transcriptRecord' => function($q) {
                        return $q->with(['curriculum', 'studentCurriculum']);
                    }]);
                },
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

    public function approve(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            // POST -> payload is { approval_notes: '' } /evaluations/:evaluationId/approve -> update academic record status to Evaluation Approved(id 4) 
            // then update approval_notes, approved_date, approved_by in evaluation. also if there is an application update the application step id to Academic Record Application(id 7)

            $evaluation = Evaluation::find($id);
            $data['approved_date'] = Carbon::now();
            $data['approved_by'] = Auth::user()->id;
            $evaluation->update($data);
            $evaluationApprovedStatus = Config::get('constants.academic_record_status.EVALUATION_APPROVED');

            $academicRecord = $evaluation->academicRecord;
            $academicRecord->update([
                'academic_record_status_id' => $evaluationApprovedStatus
            ]);

            $student = $evaluation->student;
            if ($student && $student->is_onboarding) {
                $academicRecordApplicationStep = Config::get('constants.onboarding_step.ACADEMIC_RECORD_APPLICATION');
                $student->update([                 
                    'onboarding_step_id' => $academicRecordApplicationStep
                ]);
            }

            DB::commit();
            return $evaluation;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during EvaluationService reject method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function reject(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $evaluation = Evaluation::find($id);
            $data['disapproved_date'] = Carbon::now();
            $data['disapproved_by'] = Auth::user()->id;
            $evaluation->update($data);
            $evaluationRejectedStatus = Config::get('constants.academic_record_status.EVALUATION_REJECTED');

            $academicRecord = $evaluation->academicRecord;
            $academicRecord->update([
                'academic_record_status_id' => $evaluationRejectedStatus
            ]);

            $student = $evaluation->student;
            if ($student && $student->is_onboarding) {
                $requestEvaluationStep = Config::get('constants.onboarding_step.REQUEST_EVALUATION');
                $student->update([
                    'onboarding_step_id' => $requestEvaluationStep
                ]);
            }
            
            DB::commit();
            return $evaluation;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during EvaluationService reject method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
