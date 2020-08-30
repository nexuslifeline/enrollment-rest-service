<?php

namespace App\Services;

use App\Student;
use App\Transcript;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TranscriptService
{
    public function list(bool $isPaginated, int $perPage, array $filters)
    {
        try {
            $query = Transcript::with([
              'section',
              'schoolYear',
              'level',
              'course',
              'semester',
              'schoolCategory',
              'studentCategory',
              'studentType',
              'application',
              'admission',
              'student' => function($query) {
                  $query->with(['address', 'photo']);
              }]);

            // filters
            // student
            $studentId = $filters['student_id'] ?? false;
            $query->when($studentId, function($q) use ($studentId) {
                return $q->whereHas('student', function($query) use ($studentId) {
                    return $query->where('student_id', $studentId);
                });
            });

            // course
            $courseId = $filters['course_id'] ?? false;
            $query->when($courseId, function($q) use ($courseId) {
                return $q->whereHas('course', function($query) use ($courseId) {
                    return $query->where('course_id', $courseId);
                });
            });

            // school category
            $schoolCategoryId = $filters['school_category_id'] ?? false;
            $query->when($schoolCategoryId, function($q) use ($schoolCategoryId) {
                return $q->whereHas('schoolCategory', function($query) use ($schoolCategoryId) {
                    return $query->where('school_category_id', $schoolCategoryId);
                });
            });

            // application status
            $applicationStatusId = $filters['application_status_id'] ?? false;
            $query->when($applicationStatusId, function($q) use ($applicationStatusId) {
                return $q->where(function($q) use ($applicationStatusId) {
                    return $q->whereHas('application', function($query) use ($applicationStatusId) {
                        return $query->where('application_status_id', $applicationStatusId);
                    })->orWhereHas('admission', function($query) use ($applicationStatusId) {
                        return $query->where('application_status_id', $applicationStatusId);
                    });
                });
            });

            // transcript status
            $transcriptStatusId = $filters['transcript_status_id'] ?? false;
            $query->when($transcriptStatusId, function($query) use ($transcriptStatusId) {
                return $query->where('transcript_status_id', $transcriptStatusId);
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
            $transcripts = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();
            return $transcripts;
        } catch (Exception $e) {
            Log::info('Error occured during TranscriptService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, array $transcriptInfo, int $id)
    {
        DB::beginTransaction();
        try {
            $transcript = Transcript::find($id);

            $transcript->update($data);

            if ($transcriptInfo['application'] ?? false) {
                $application = $transcript->application();
                if ($application) {
                    $application->update($transcriptInfo['application']);
                }
            }

            if ($transcriptInfo['admission'] ?? false) {
                $admission = $transcript->admission();
                if ($admission) {
                    $admission->update($transcriptInfo['admission']);
                }
            }

            if ($transcriptInfo['student_fee'] ?? false) {
                $student = $transcript->student()->first();
                $studentFee = $student->studentFees()
                    ->updateOrCreate(['transcript_id' => $transcript->id], $transcriptInfo['student_fee']);

                if ($transcriptInfo['fees'] ?? false) {
                    $fees = $transcriptInfo['fees'];
                    $items = [];
                    foreach ($fees as $fee) {
                        $items[$fee['school_fee_id']] = [
                            'amount' => $fee['amount'],
                            'notes' => $fee['notes']
                        ];
                    }
                    $studentFee->studentFeeItems()->sync($items);
                }
            }

            if ($transcriptInfo['billing'] ?? false) {
                $billing = $studentFee->billings()->create($transcriptInfo['billing']);

                if ($transcriptInfo['billing_item'] ?? false) {
                    $billing->billingItems()->create($transcriptInfo['billing_item']);
                }

                $billing->update([
                    'billing_no' => 'BILL-'. date('Y') .'-'. str_pad($billing->id, 7, '0', STR_PAD_LEFT)
                ]);
            }

            if ($transcriptInfo['subjects'] ?? false) {
                $transcript->subjects()->sync($transcriptInfo['subjects']);
            }
            DB::commit();
            $transcript->load([
                'schoolYear',
                'level',
                'course',
                'semester',
                'schoolCategory',
                'studentCategory',
                'studentType',
                'application',
                'admission',
                'student' => function($query) {
                    $query->with(['address']);
                }])->fresh();
            return $transcript;
          } catch (Exception $e) {
            DB::rollBack();
            Log::info('Error occured during TranscriptService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getTranscriptsOfStudent(int $studentId, bool $isPaginated, int $perPage, array $filters)
    {
        try {
            $transcript = Student::find($studentId)->transcripts();
            $query = $transcript->with([
                'section',
                'schoolYear',
                'level',
                'course',
                'semester',
                'schoolCategory',
                'studentCategory',
                'studentType',
                'application',
                'admission',
                'student' => function($query) {
                    $query->with(['address', 'photo']);
                }]);

            // transcript status
            $transcriptStatusId = $filters['transcript_status_id'] ?? false;
            $query->when($transcriptStatusId, function($query) use ($transcriptStatusId) {
                return $query->where('transcript_status_id', $transcriptStatusId);
            });

            $transcripts = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();
            return $transcripts;
        } catch (Exception $e) {
            Log::info('Error occured during TranscriptService getTranscriptsOfStudent method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}