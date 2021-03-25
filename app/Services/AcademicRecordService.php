<?php

namespace App\Services;

use App\Student;
use App\AcademicRecord;
use App\Evaluation;
use App\Payment;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Auth;
use Carbon\Carbon;

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
                'student' => function ($query) {
                    $query->with(['address', 'photo']);
                }
            ]);

            // filters
            // student
            $studentId = $filters['student_id'] ?? false;
            $query->when($studentId, function ($q) use ($studentId) {
                return $q->whereHas('student', function ($query) use ($studentId) {
                    return $query->where('student_id', $studentId);
                });
            });

            // schoolYear
            $schoolYearId = $filters['school_year_id'] ?? false;
            $query->when($schoolYearId, function ($q) use ($schoolYearId) {
                return $q->whereHas('schoolYear', function ($query) use ($schoolYearId) {
                    return $query->where('school_year_id', $schoolYearId);
                });
            });


            // school category
            $schoolCategoryId = $filters['school_category_id'] ?? false;
            $query->when($schoolCategoryId, function ($q) use ($schoolCategoryId) {
                return $q->whereHas('schoolCategory', function ($query) use ($schoolCategoryId) {
                    return $query->where('school_category_id', $schoolCategoryId);
                });
            });

            // level
            $levelId = $filters['level_id'] ?? false;
            $query->when($levelId, function ($q) use ($levelId) {
                return $q->whereHas('level', function ($query) use ($levelId) {
                    return $query->where('level_id', $levelId);
                });
            });

            // course
            $courseId = $filters['course_id'] ?? false;
            $query->when($courseId, function ($q) use ($courseId) {
                return $q->whereHas('course', function ($query) use ($courseId) {
                    return $query->where('course_id', $courseId);
                });
            });

            // semester
            $semesterId = $filters['semester_id'] ?? false;
            $query->when($semesterId, function ($q) use ($semesterId) {
                return $q->whereHas('semester', function ($query) use ($semesterId) {
                    return $query->where('semester_id', $semesterId);
                });
            });

            // application status
            $applicationStatusId = $filters['application_status_id'] ?? false;
            $query->when($applicationStatusId, function ($q) use ($applicationStatusId) {
                return $q->where(function ($q) use ($applicationStatusId) {
                    return $q->whereHas('application', function ($query) use ($applicationStatusId) {
                        return $query->where('application_status_id', $applicationStatusId);
                    })->orWhereHas('admission', function ($query) use ($applicationStatusId) {
                        return $query->where('application_status_id', $applicationStatusId);
                    });
                });
            });

            //not equals to application status
            $notApplicationStatusId = $filters['not_application_status_id'] ?? false;
            $query->when($notApplicationStatusId, function ($q) use ($notApplicationStatusId) {
                return $q->where(function ($q) use ($notApplicationStatusId) {
                    return $q->whereHas('application', function ($query) use ($notApplicationStatusId) {
                        return $query->where('application_status_id', '!=', $notApplicationStatusId);
                    })->orWhereHas('admission', function ($query) use ($notApplicationStatusId) {
                        return $query->where('application_status_id', '!=', $notApplicationStatusId);
                    });
                });
            });

            // academicRecord status
            $academicRecordStatusId = $filters['academic_record_status_id'] ?? false;
            $query->when($academicRecordStatusId, function ($query) use ($academicRecordStatusId) {
                return $query->where('academic_record_status_id', $academicRecordStatusId);
            });

            // not equals to academicRecord status
            $notAcademicRecordStatusId = $filters['not_academic_record_status_id'] ?? false;
            $query->when($notAcademicRecordStatusId, function ($query) use ($notAcademicRecordStatusId) {
                return $query->where('academic_record_status_id', '!=', $notAcademicRecordStatusId);
            });

            //is manual
            $isManual = $filters['is_manual'] ?? false;
            $query->when($isManual, function ($query) use ($isManual) {
                return $query->where('is_manual', $isManual);
            });

            //manual steps
            $manualStepId = $filters['manual_step_id'] ?? false;
            $query->when($manualStepId, function ($query) use ($manualStepId) {
                return $query->where('manual_step_id', $manualStepId);
            });

            //not equals to manual steps
            $notManualStepId = $filters['not_manual_step_id'] ?? false;
            $query->when($notManualStepId, function ($query) use ($notManualStepId) {
                return $query->where('manual_step_id', '!=', $notManualStepId);
            });

            // filter by student name
            $criteria = $filters['criteria'] ?? false;
            $query->when($criteria, function ($q) use ($criteria) {
                return $q->whereHas('student', function ($query) use ($criteria) {
                    // return $query->where(function ($q) use ($criteria) {
                    //     return $q->where('name', 'like', '%' . $criteria . '%')
                    //         ->orWhere('student_no', 'like', '%' . $criteria . '%')
                    //         ->orWhere('first_name', 'like', '%' . $criteria . '%')
                    //         ->orWhere('middle_name', 'like', '%' . $criteria . '%')
                    //         ->orWhere('last_name', 'like', '%' . $criteria . '%');
                    // });

                    //scopedWhereLike on student model
                    $query->whereLike($criteria);
                });
            });

            // order by
            $orderBy = $filters['order_by'] ?? false;
            $query->when($orderBy, function ($q) use ($orderBy, $filters) {
                $sort = $filters['sort'] ?? 'ASC';
                return $q->orderBy($orderBy, $sort);
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

    public function get(int $id)
    {
        try {
            $academicRecord = AcademicRecord::with([
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
                'studentFee' => function ($query) {
                    $query->with(['studentFeeItems']);
                },
                'subjects' => function ($q) use ($id) {
                    return $q->with(['grades' => function ($q) use ($id) {
                        $q->wherePivot('academic_record_id', $id);
                    }]);
                }, 
                // 'grades',
                'student' => function ($query) {
                    $query->with(['address', 'photo']);
                }
            ])->find($id);
            return $academicRecord;
        } catch (Exception $e) {
            Log::info('Error occured during AcademicRecordService get method call: ');
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
                    if ($academicRecord->academic_record_status_id === 2) {
                        $academicRecordInfo['application']['approved_by'] = Auth::user()->id;
                        $academicRecordInfo['application']['approved_date'] = Carbon::now();
                    }
                    if ($academicRecordInfo['application']['application_status_id'] ?? false) {
                        if ($academicRecordInfo['application']['application_status_id'] === 3) {
                            $academicRecordInfo['application']['disapproved_by'] = Auth::user()->id;
                            $academicRecordInfo['application']['disapproved_date'] = Carbon::now();
                        }
                    }
                    $application->update($academicRecordInfo['application']);
                }
            }

            if ($academicRecordInfo['admission'] ?? false) {
                $admission = $academicRecord->admission();
                if ($admission) {
                    if ($academicRecord->academic_record_status_id === 2) {
                        $academicRecordInfo['admission']['approved_by'] = Auth::user()->id;
                        $academicRecordInfo['admission']['approved_date'] = Carbon::now();
                    }
                    if ($academicRecordInfo['admission']['application_status_id'] ?? false) {
                        if ($academicRecordInfo['admission']['application_status_id'] === 3) {
                            $academicRecordInfo['admission']['disapproved_by'] = Auth::user()->id;
                            $academicRecordInfo['admission']['disapproved_date'] = Carbon::now();
                        }
                    }
                    $admission->update($academicRecordInfo['admission']);
                }
            }

            if ($academicRecordInfo['student_fee'] ?? false) {
                $student = $academicRecord->student()->first();
                if ($academicRecordInfo['student_fee']['student_fee_status_id'] === 2) {
                    $academicRecordInfo['student_fee']['approved_by'] = Auth::user()->id;
                    $academicRecordInfo['student_fee']['approved_date'] = Carbon::now();
                }
                $studentFee = $student->studentFees()
                    ->updateOrCreate(['academic_record_id' => $academicRecord->id], $academicRecordInfo['student_fee']);

                if (array_key_exists('fees', $academicRecordInfo)) {
                    $fees = $academicRecordInfo['fees'];
                    $items = [];
                    foreach ($fees as $fee) {
                        $items[$fee['school_fee_id']] = [
                            'amount' => $fee['amount'],
                            'notes' => $fee['notes'],
                            'is_initial_fee' => $fee['is_initial_fee']
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

                if ($billing->previous_balance > 0 && $billing->billing_type_id === 1) {
                    $studentFee->recomputeTerms();
                }

                $billing->update([
                    'billing_no' => 'BILL-' . date('Y') . '-' . str_pad($billing->id, 7, '0', STR_PAD_LEFT)
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
                'student' => function ($query) {
                    $query->with(['address']);
                }
            ])->fresh();
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
                'student' => function ($query) {
                    $query->with(['address', 'photo']);
                }
            ]);

            // academicRecord status
            $academicRecordStatusId = $filters['academic_record_status_id'] ?? false;
            $query->when($academicRecordStatusId, function ($query) use ($academicRecordStatusId) {
                return $query->where('academic_record_status_id', $academicRecordStatusId);
            });

            // application status
            $applicationStatusId = $filters['application_status_id'] ?? false;
            $query->when($applicationStatusId, function ($q) use ($applicationStatusId) {
                return $q->where(function ($q) use ($applicationStatusId) {
                    return $q->whereHas('application', function ($query) use ($applicationStatusId) {
                        return $query->where('application_status_id', $applicationStatusId);
                    })->orWhereHas('admission', function ($query) use ($applicationStatusId) {
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

    public function getPendingApprovalCount()
    {
        $data['evaluation'] = Evaluation::where('evaluation_status_id', 2)->count();
        $data['enlistment'] = AcademicRecord::where(function ($q) {
            return $q->whereHas('application', function ($query) {
                return $query->where('application_status_id', 4);
            })->orWhereHas('admission', function ($query) {
                return $query->where('application_status_id', 4);
            });
        })->where('academic_record_status_id', 1)->count();
        $data['assessment'] = AcademicRecord::where(function ($q) {
            return $q->whereHas('application', function ($query) {
                return $query->where('application_status_id', 4);
            })->orWhereHas('admission', function ($query) {
                return $query->where('application_status_id', 4);
            });
        })->where('academic_record_status_id', 2)->count();
        $data['payment'] = Payment::where('payment_status_id', 4)->count();

        return $data;
    }


    public function getGradesOfAcademicRecords(int $subjectId, int $sectionId, bool $isPaginated, int $perPage, array $filters)
    {
        try {
            $query = AcademicRecord::where('academic_record_status_id', 3)
            ->with(['grades' => function ($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
            }, 'student'])
            ->whereHas('subjects', function ($q) use ($sectionId) {
                return $q->where('section_id', $sectionId);
            })->whereHas('grades', function ($q) use ($subjectId) {
                return $q->where('subject_id', $subjectId);
            });

            $criteria = $filters['criteria'] ?? false;
            $query->when($criteria, function ($q) use ($criteria) {
                return $q->where(function ($q) use ($criteria) {
                    return $q->whereHas('student', function ($q) use ($criteria) {
                        return $q->where('first_name', 'LIKE', '%' . $criteria . '%')
                            ->orWhere('middle_name', 'LIKE', '%' . $criteria . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $criteria . '%')
                            ->orWhere('student_no', 'LIKE', '%' . $criteria . '%');
                    });
                });
            });

            $academicRecords = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();
            return $academicRecords;
        } catch (Exception $e) {
            Log::info('Error occured during AcademicRecordService getGradesOfAcademicRecords method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function gradeBatchUpdate(array $data)
    {
        DB::beginTransaction();
        try {
            $result = [];
            foreach ($data as $value) {
                $academicRecord = AcademicRecord::find($value['id']);
                if ($value['grades']) {
                    $items = [];
                    foreach ($value['grades'] as $grade) {
                        $items[$grade['term_id']] = [
                            'personnel_id' => Auth::user()->userable->id,
                            'grade' => $grade['grade']
                            // 'notes' => $detail['notes']
                        ];
                    }
                    $academicRecord->grades()->wherePivot('subject_id', $value['subject_id'])->sync($items);
                }
                $result[] = $academicRecord;
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
