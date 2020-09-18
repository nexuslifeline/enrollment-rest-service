<?php

namespace App\Services;

use App\Student;
use App\AcademicRecord;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AcademicRecordService
{
    public function list(bool $isPaginated, int $perPage, array $filters)
    {
        try {
            $query = AcademicRecord::with([
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

            // academicRecord status
            $academicRecordStatusId = $filters['academic_record_status_id'] ?? false;
            $query->when($academicRecordStatusId, function($query) use ($academicRecordStatusId) {
                return $query->where('academic_record_status_id', $academicRecordStatusId);
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
            $academicRecords = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();
            return $academicRecords;
        } catch (Exception $e) {
            Log::info('Error occured during AcademicRecordService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, array $academicRecordInfo, int $id)
    {
        DB::beginTransaction();
        try {
            $academicRecord = AcademicRecord::find($id);

            $academicRecord->update($data);

            if ($academicRecordInfo['application'] ?? false) {
                $application = $academicRecord->application();
                if ($application) {
                    $application->update($academicRecordInfo['application']);
                }
            }

            if ($academicRecordInfo['admission'] ?? false) {
                $admission = $academicRecord->admission();
                if ($admission) {
                    $admission->update($academicRecordInfo['admission']);
                }
            }

            if ($academicRecordInfo['student_fee'] ?? false) {
                $student = $academicRecord->student()->first();
                $studentFee = $student->studentFees()
                    ->updateOrCreate(['academic_record_id' => $academicRecord->id], $academicRecordInfo['student_fee']);

                if (array_key_exists('fees', $academicRecordInfo)) {
                    $fees = $academicRecordInfo['fees'];
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

            if ($academicRecordInfo['billing'] ?? false) {
                $billing = $studentFee->billings()->create($academicRecordInfo['billing']);

                if ($academicRecordInfo['billing_item'] ?? false) {
                    $billing->billingItems()->create($academicRecordInfo['billing_item']);
                }

                $billing->update([
                    'billing_no' => 'BILL-'. date('Y') .'-'. str_pad($billing->id, 7, '0', STR_PAD_LEFT)
                ]);
            }

            if (array_key_exists('subjects', $academicRecordInfo)) {
                $items = [];
                $subjects = $academicRecordInfo['subjects'];
                foreach ($subjects as $subject) {
                    $items[$subject['subject_id']] = [
                        'section_id' => $subject['section_id'],
                    ];
                }
                $academicRecord->subjects()->sync($items);
            }
            DB::commit();
            $academicRecord->load([
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
            return $academicRecord;
          } catch (Exception $e) {
            DB::rollBack();
            Log::info('Error occured during AcademicRecordService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getAcademicRecordsOfStudent(int $studentId, bool $isPaginated, int $perPage, array $filters)
    {
        try {
            $academicRecord = Student::find($studentId)->academicRecords();
            $query = $academicRecord->with([
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

            // academicRecord status
            $academicRecordStatusId = $filters['academic_record_status_id'] ?? false;
            $query->when($academicRecordStatusId, function($query) use ($academicRecordStatusId) {
                return $query->where('academic_record_status_id', $academicRecordStatusId);
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

            $academicRecords = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();
            return $academicRecords;
        } catch (Exception $e) {
            Log::info('Error occured during AcademicRecordService getAcademicRecordsOfStudent method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}