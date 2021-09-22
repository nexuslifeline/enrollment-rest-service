<?php

namespace App\Services;

use Auth;
use Exception;
use App\Student;
use Carbon\Carbon;
use App\Evaluation;
use App\AcademicRecord;
use App\Billing;
use App\Curriculum;
use App\Level;
use App\Payment;
use App\SchoolYear;
use App\Semester;
use App\TranscriptRecord;
use App\Services\BillingService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\ValidationException;

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
                'studentFee',
                'student' => function ($query) {
                    $query->orderBy('first_name', 'ASC')->with(['address', 'photo', 'user']);
                }
            ])->select('academic_records.*');

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

            // section
            $sectionId = $filters['section_id'] ?? false;
            $query->when($sectionId, function ($q) use ($sectionId) {
                return $q->whereHas('section', function ($query) use ($sectionId) {
                    return $query->where('section_id', $sectionId);
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
            // $applicationStatusId = $filters['application_status_id'] ?? false;
            // $query->when($applicationStatusId, function ($q) use ($applicationStatusId) {
            //     return $q->where(function ($q) use ($applicationStatusId) {
            //         return $q->whereHas('application', function ($query) use ($applicationStatusId) {
            //             return $query->where('application_status_id', $applicationStatusId);
            //         })->orWhereHas('admission', function ($query) use ($applicationStatusId) {
            //             return $query->where('application_status_id', $applicationStatusId);
            //         });
            //     });
            // });

            //not equals to application status
            // $notApplicationStatusId = $filters['not_application_status_id'] ?? false;
            // $query->when($notApplicationStatusId, function ($q) use ($notApplicationStatusId) {
            //     return $q->where(function ($q) use ($notApplicationStatusId) {
            //         return $q->whereHas('application', function ($query) use ($notApplicationStatusId) {
            //             return $query->where('application_status_id', '!=', $notApplicationStatusId);
            //         })->orWhereHas('admission', function ($query) use ($notApplicationStatusId) {
            //             return $query->where('application_status_id', '!=', $notApplicationStatusId);
            //         });
            //     });
            // });

            // academicRecord status
            $academicRecordStatusId = $filters['academic_record_status_id'] ?? false;
            $query->when($academicRecordStatusId, function ($query) use ($academicRecordStatusId) {
                if (!is_array($academicRecordStatusId)) {
                    return $query->where('academic_record_status_id', $academicRecordStatusId);
                } else {
                    return $query->whereIn('academic_record_status_id', $academicRecordStatusId);
                }
            });

            // not equals to academicRecord status
            // $notAcademicRecordStatusId = $filters['not_academic_record_status_id'] ?? false;
            // $query->when($notAcademicRecordStatusId, function ($query) use ($notAcademicRecordStatusId) {
            //     return $query->where('academic_record_status_id', '!=', $notAcademicRecordStatusId);
            // });

            //is manual
            $isManual = Arr::exists($filters, 'is_manual') == 1 ? $filters['is_manual'] : false;
            $query->when(Arr::exists($filters, 'is_manual') == 1, function ($q) use ($isManual) {
                return $q->where('is_manual', $isManual);
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
            // $orderBy = $filters['order_by'] ?? false;
            // $query->when($orderBy, function ($q) use ($orderBy, $filters) {
            //     $sort = $filters['sort'] ?? 'ASC';
            //     return $q->orderBy($orderBy, $sort);
            // });

            $orderBy = 'id';
            $sort = 'DESC';

            $ordering = $filters['ordering'] ?? false;
            if ($ordering) {
                $isDesc = str_starts_with($ordering, '-');
                $orderBy = $isDesc ? substr($ordering, 1) : $ordering;
                $sort = $isDesc ? 'DESC' : 'ASC';
            }

            $studentFields = ['first_name', 'last_name', 'complete_address', 'city', 'barangay', 'region'];
            $courseFields = ['course_name'];
            $levelFields = ['level_name'];
            $studentCategoryFields = ['student_category_name'];
            
            if (in_array($orderBy, $studentFields)) {
                $query->orderByStudent($orderBy, $sort);
            } else if (in_array($orderBy, $courseFields)) {
                $query->orderByCourse($orderBy, $sort);
            } else if (in_array($orderBy, $levelFields)) {
                $query->orderByLevel($orderBy, $sort);
            } else if (in_array($orderBy, $studentCategoryFields)) {
                $query->orderByStudentCategory($orderBy, $sort);
            } 
            else {
                $query->orderBy($orderBy, $sort);
            }
            

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
                'transcriptRecord' => function ($q) {
                    $q->with('curriculum');
                },
                'studentFee' => function ($query) {
                    $query->with(['studentFeeItems']);
                },
                'subjects' => function ($q) use ($id) {
                    $q->wherePivot('academic_record_id', $id);
                    // return $q->with(['grades' => function ($q) use ($id) {
                    //     $q->wherePivot('academic_record_id', $id);
                    // }]);
                },
                // 'grades',
                'student' => function ($query) {
                    $query->with(['address', 'photo', 'user']);
                },
            ])->find($id);
            return $academicRecord;
        } catch (Exception $e) {
            Log::info('Error occured during AcademicRecordService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function quickEnroll(array $data, int $studentId )
    {
        DB::beginTransaction();
        try {

            $schoolCategoryId  = $data['school_category_id'];
            $schoolYearId  = $data['school_year_id'];
            $levelId  = $data['level_id'] ?? null;
            $courseId  = $data['course_id'] ?? null;
            $semesterId  = $data['semester_id'] ?? null;

            if (!$studentId) {
                throw new Exception('Student id not found!');
            }

            if (!$schoolCategoryId) {
                throw new Exception('School Category id not found!');
            }

            if (!$schoolYearId) {
                throw new Exception('School Year id not found!');
            }

            $enrolledStatus = Config::get('constants.academic_record_status.ENROLLED');
            $academicRecords = AcademicRecord::when($levelId, function ($q) use ($levelId) {
                return $q->where('level_id', $levelId);
            })->when($schoolYearId, function ($q) use ($schoolYearId){
                return $q->where('school_year_id', $schoolYearId);
            })->when($courseId, function ($q) use ($courseId) {
                return $q->where('course_id', $courseId);
            })->when($semesterId, function ($q) use ($semesterId) {
                return $q->where('semester_id', $semesterId);
            })
            ->where('academic_record_status_id', $enrolledStatus)
            ->where('student_id', $studentId)
                ->count();

            if ($academicRecords > 0) {
                throw ValidationException::withMessages([
                    'non_field_error' => ['Student is already been enrolled.']
                ]);
            }
            

            $transcriptRecord = Student::find($studentId)->transcriptRecords()
                ->where('transcript_record_status_id', 1) //active transcript
                ->latest()
                ->first();

            $transcriptRecordId = $transcriptRecord ? $transcriptRecord->id
                : TranscriptRecord::create([
                    'student_id' => $studentId,
                    'school_category_id' => $schoolCategoryId,
                    'transcript_record_status_id' => 1 //draft
                ])->id;


            $academicRecord = AcademicRecord::create([
                'student_id' => $studentId,
                'transcript_record_id' => $transcriptRecordId,
                'school_category_id' => $schoolCategoryId,
                'school_year_id' => $schoolYearId,
                'academic_record_status_id' => 1,
                'manual_step_id' => 1,
                'level_id' => $levelId,
                'course_id' => $courseId,
                'semester_id' => $semesterId
            ]);

            DB::commit();
            return $academicRecord;
        } catch (Exception $e) {
            DB::rollBack();
            Log::info('Error occured during AcademicRecordService store method call: ');
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

            // if ($academicRecordInfo['admission'] ?? false) {
            //     $admission = $academicRecord->admission();
            //     if ($admission) {
            //         if ($academicRecord->academic_record_status_id === 2) {
            //             $academicRecordInfo['admission']['approved_by'] = Auth::user()->id;
            //             $academicRecordInfo['admission']['approved_date'] = Carbon::now();
            //         }
            //         if ($academicRecordInfo['admission']['application_status_id'] ?? false) {
            //             if ($academicRecordInfo['admission']['application_status_id'] === 3) {
            //                 $academicRecordInfo['admission']['disapproved_by'] = Auth::user()->id;
            //                 $academicRecordInfo['admission']['disapproved_date'] = Carbon::now();
            //             }
            //         }
            //         $admission->update($academicRecordInfo['admission']);
            //     }
            // }

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
                    'billing_no' => 'BN-' . date('Y') . '-' . str_pad($billing->id, 7, '0', STR_PAD_LEFT)
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

            if ($academicRecordInfo['transcript_record'] ?? false) {
                $academicRecord->transcriptRecord->update($academicRecordInfo['transcript_record']);
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
                // 'curriculum',
                'transcriptRecord' => function($q) {
                    return $q->with(['curriculum']);
                },
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
                    });
                    // ->orWhereHas('admission', function ($query) use ($applicationStatusId) {
                    //     return $query->where('application_status_id', $applicationStatusId);
                    // });
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

    public function getPendingApprovalCount(array $filters)
    {
        $schoolYearId = $filters['school_year_id'] ?? false;
        $enlistmentPendingStatus = Config::get('constants.academic_record_status.ENLISTMENT_PENDING');
        $enlistmentApprovedStatus = Config::get('constants.academic_record_status.ENLISTMENT_APPROVED');
        $draft = Config::get('constants.academic_record_status.DRAFT');
        $evaluationPending = Config::get('constants.academic_record_status.EVALUATION_PENDING');
        $pendingStatus = Config::get('constants.payment_status.PENDING');
        $evaluation = Evaluation::whereHas('academicRecord', function ($q) use ($evaluationPending) {
            return $q->whereIn('academic_record_status_id', [$evaluationPending]);
        })
        ->when($schoolYearId, function($q) use($schoolYearId) {
            return $q->whereHas('academicRecord', function ($query) use($schoolYearId) {
                return $query->where('school_year_id', $schoolYearId);
            });
        });

        $enlistment = AcademicRecord::where('academic_record_status_id', $enlistmentPendingStatus)
        ->when($schoolYearId, function($q) use($schoolYearId) {
            return $q->where('school_year_id', $schoolYearId);
        });


        $assessment = AcademicRecord::where('academic_record_status_id', $enlistmentApprovedStatus)
        ->when($schoolYearId, function($q) use($schoolYearId) {
            return $q->where('school_year_id', $schoolYearId);
        });

        $data['payment'] = Payment::where('payment_status_id', $pendingStatus)
        ->count();


        $data['evaluation'] = $evaluation->count();
        $data['enlistment']  = $enlistment->count();
        $data['assessment'] = $assessment->count();

        return $data;
    }


    public function getGradesOfAcademicRecords(int $subjectId, int $sectionId, bool $isPaginated, int $perPage, array $filters)
    {
        try {
            $enrolledStatus = Config::get('constants.academic_record_status.ENROLLED');
            $query = AcademicRecord::where('academic_record_status_id', $enrolledStatus)
            ->with(['grades' => function ($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
            }, 'student'])
            ->whereHas('subjects', function ($q) use ($sectionId) {
                return $q->where('section_id', $sectionId);
            })->whereHas('grades', function ($q) use ($subjectId) {
                return $q->where('subject_id', $subjectId)
                    ->where('student_grade_status_id', 1);
            });

            $criteria = $filters['criteria'] ?? false;
            $query->when($criteria, function ($q) use ($criteria) {
                return $q->where(function ($q) use ($criteria) {
                    return $q->whereHas('student', function ($q) use ($criteria) {
                        return $q->whereLike($criteria);
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

    public function getSubjects(int $academicRecordId, bool $isPaginated, int $perPage, array $filters)
    {

        try {
            $query = AcademicRecord::find($academicRecordId)->subjects();
            $query->withPivot(['is_dropped']);

            $subjects = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();
            $subjects->append(['sectionSchedule','section']);
            return $subjects;
        } catch (Exception $e) {
            Log::info('Error occured during SubjectService getSubjects method call: ');
            Log::info($e->getMessage());
            throw $e;
        }

    }


    public function updateSubject(array $data = [], int $academicRecordId, int $subjectId)
    {
        try {
            $academicRecord = AcademicRecord::find($academicRecordId);

            if (Arr::exists($data, 'is_dropped') && $data['is_dropped'] === 1) {
                if (!$academicRecord->schoolYear->is_active) {
                    throw ValidationException::withMessages([
                        'non_field_error' => ["Subject cannot be mark as dropped because the subject's academic school year is not active."]
                    ]);
                }
            }

            $subject = $academicRecord->subjects()
                ->where('subject_id', $subjectId)
                ->first();

            if (count($data) > 0) {
                $pivot = $subject->pivot;
                foreach ($data as $key => $value) {
                    $pivot->{$key} = $value;
                }
                $pivot->save();
            }

            $subject->append('section');
            return $subject;
        } catch (Exception $e) {
            Log::info('Error occured during SubjectService getSubjects method call: ');
            Log::info($e->getMessage());
            throw $e;
        }

    }

    public function deleteSubject(int $academicRecordId, int $subjectId)
    {
        try {
            $academicRecord = AcademicRecord::find($academicRecordId);
            $academicRecordStatuses = [
                Config::get('constants.academic_record_status.ASSESSMENT_APPROVED'),
                Config::get('constants.academic_record_status.PAYMENT_SUBMITTED'),
                Config::get('constants.academic_record_status.ENROLLED'),
                Config::get('constants.academic_record_status.CLOSED'),
            ];
            if (in_array($academicRecord->academic_record_status_id, $academicRecordStatuses)) {
                if (!$academicRecord->schoolYear->is_active) {
                    throw ValidationException::withMessages([
                        'non_field_error' => ["Subject cannot be deleted."]
                    ]);
                }
            }
            $academicRecord->subjects()
                ->wherePivot('subject_id', $subjectId)
                ->detach();
        } catch (Exception $e) {
            Log::info('Error occured during SubjectService getSubjects method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function syncSubjectsOfAcademicRecord(int $academicRecordId, array $subjects)
    {
        DB::beginTransaction();
        try {
            $academicRecord = AcademicRecord::find($academicRecordId);
            $items = [];
            foreach ($subjects as $subject) {
                $items[$subject['subject_id']] = [
                    'section_id' => $subject['section_id'],
                ];
            }
            $subjects = $academicRecord->subjects();
            $subjects->sync($items);
            DB::commit();
            return $subjects->get();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SubjectService syncSubjectsOfAcademicRecord method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getInitialBilling(int $academicRecordId)
    {
        $initialBillingType = Config::get('constants.billing_type.INITIAL_FEE');
        // $approvePaymentStatus = Config::get('constants.payment_status.APPROVED');
        // Note! update whereHas once academic_record_id id is added in billing table
        $billing = Billing::with('payments')
            ->whereHas('studentFee', function ($q) use ($academicRecordId) {
                return $q->where('academic_record_id', $academicRecordId);
            })
            ->where('billing_type_id', $initialBillingType)
            ->first();

        if (!$billing) {
            throw ValidationException::withMessages([
                'non_field_error' => ['No Initial Billing found!']
            ]);
        }

        if ($billing->payments->count() === 0) {
            $billing->payments()->create([
                    'school_year_id' => $billing->studentFee->academicRecord->school_year_id,
                    'student_id' => $billing->studentFee->academicRecord->student_id,
                    'payment_status_id' => Config::get('constants.payment_status.DRAFT'),
                    'payment_mode_id' => Config::get('constants.payment_mode.BANK')
                ]
            );
        }
        // // If there is payment, this means the initial billing has been fully paid since we dont accept payment less than its initial fee
        // $billing->is_paid = $billing->payments &&
        //     $billing->payments->count() > 0 &&
        //     $billing->payments[0]->payment_status_id === $approvePaymentStatus;
        

        $billing->load(['payments','studentFee']);

        return $billing;
    }

    public function requestEvaluation(array $data, array $evaluationData, int $academicRecordId)
    {
        DB::beginTransaction();
        try {
            $academicRecord = AcademicRecord::find($academicRecordId);
            // $application = $academicRecord->application;

            $evaluation = $academicRecord->evaluation;
            $evaluationPendingStatus = Config::get('constants.academic_record_status.EVALUATION_PENDING');

            $level = Level::find($data['level_id']);
            $data['academic_record_status_id'] = $evaluationPendingStatus;
            $data['school_category_id'] = $level->school_category_id;

            if ($academicRecord->is_admission) {
                $curriculum = Curriculum::where('course_id', $data['course_id'])
                    ->whereHas('subjects', function ($q) use ($data, $level) {
                        return $q->where('level_id', $data['level_id'])
                            ->where('semester_id', $data['semester_id'])
                            ->where('curriculum_subjects.school_category_id', $level->school_category_id);
                    })->where('active', 1)->first();
                if ($curriculum) {
                    $academicRecord->transcriptRecord->update([
                        'curriculum_id' => $curriculum->id
                    ]);
                }
            }

            $academicRecord->update($data);

            $evaluationData['submitted_date'] = Carbon::now();
            $evaluation->update($evaluationData);

            $student = $academicRecord->student;
            if ($student && $student->is_onboarding) {
                $evaluationReview = Config::get('constants.onboarding_step.EVALUATION_IN_REVIEW');
                $student->update([
                    'onboarding_step_id' => $evaluationReview
                ]);
            }

            DB::commit();
            return $evaluation->load(['academicRecord', 'lastSchoolLevel']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info('Error occured during AcademicRecordService requestEvaluation method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function submit(array $data, array $subjects, int $academicRecordId)
    {
        DB::beginTransaction();
        try {
            $academicRecord = AcademicRecord::find($academicRecordId);
            $application = $academicRecord->application;
            $enlistmentPendingStatus = Config::get('constants.academic_record_status.ENLISTMENT_PENDING');

            $data['academic_record_status_id'] = $enlistmentPendingStatus;
            $academicRecord->update($data);

            $items = [];
            foreach ($subjects as $subject) {
                $items[$subject['subject_id']] = [
                    'section_id' => $subject['section_id']
                ];
            }

            $student = $academicRecord->student;
            if ($student && $student->is_onboarding) {
                $academicRecordInReview = Config::get('constants.onboarding_step.ACADEMIC_RECORD_IN_REVIEW');
                $student->update([
                    'onboarding_step_id' => $academicRecordInReview
                ]);
            }

            $academicRecord->subjects()->sync($items);

            if ($application) {
                $application->update([
                    'applied_date' => Carbon::now()
                ]);
            }

            DB::commit();
            return $application->load('academicRecord');;
        } catch (Exception $e) {
            DB::rollBack();
            Log::info('Error occured during AcademicRecordService submit method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function approveEnlistment(array $data, array $subjects, int $academicRecordId)
    {
        DB::beginTransaction();
        try {
            $academicRecord = AcademicRecord::find($academicRecordId);

            $enlistmentApprovedStatus = Config::get('constants.academic_record_status.ENLISTMENT_APPROVED');
            $academicRecord->update([
                'academic_record_status_id' => $enlistmentApprovedStatus
            ]);

            $items = [];
            foreach ($subjects as $subject) {
                $items[$subject['subject_id']] = [
                    'section_id' => $subject['section_id']
                ];
            }

            $academicRecord->subjects()->sync($items);

            $application = $academicRecord->application;
            $data['approved_date'] = Carbon::now();
            $data['approved_by'] = Auth::id();
            if ($application) {
                $application->update($data);
            }

            $student = $academicRecord->student;
            if ($student && $student->is_onboarding) {
                $academicRecordApplication = Config::get('constants.onboarding_step.ACADEMIC_RECORD_IN_REVIEW');
                $student->update([
                    'onboarding_step_id' => $academicRecordApplication
                ]);
            }

            DB::commit();
            return $academicRecord;
        } catch (Exception $e) {
            DB::rollBack();
            Log::info('Error occured during AcademicRecordService approveEnlistment method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function requestAssessment(int $academicRecordId)
    {
        DB::beginTransaction();
        try {
            $academicRecord = AcademicRecord::find($academicRecordId);

            $enlistmentApprovedStatus = Config::get('constants.academic_record_status.ENLISTMENT_APPROVED');
            $academicRecord->update([
                'academic_record_status_id' => $enlistmentApprovedStatus
            ]);

            $studentFee = $academicRecord->studentFee();
            // Log::info($studentFee);
            $studentFee->updateOrCreate(['academic_record_id' => $academicRecord->id],
            [
                'submitted_date' => Carbon::now()
            ]);
            DB::commit();
            return $academicRecord;
        } catch (Exception $e) {
            DB::rollBack();
            Log::info('Error occured during AcademicRecordService requestAssessment method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function rejectEnlistment(array $data, int $academicRecordId)
    {
        DB::beginTransaction();
        try {
            $academicRecord = AcademicRecord::find($academicRecordId);
            $enlistmentRejectedStatus = Config::get('constants.academic_record_status.ENLISTMENT_REJECTED');
            $academicRecord->update([
                'academic_record_status_id' => $enlistmentRejectedStatus
            ]);

            $application = $academicRecord->application;
            $data['disapproved_date'] = Carbon::now();
            $data['disapproved_by'] = Auth::id();
            if ($application) {
                $application->update($data);
            }

            $student = $academicRecord->student;
            if ($student && $student->is_onboarding) {
                $academicRecordApplication = Config::get('constants.onboarding_step.ACADEMIC_RECORD_APPLICATION');
                $student->update([
                    'onboarding_step_id' => $academicRecordApplication
                ]);
            }

            DB::commit();
            return $academicRecord;
        } catch (Exception $e) {
            DB::rollBack();
            Log::info('Error occured during AcademicRecordService rejectEnlistment method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function approveAssessment(array $data, array $fees, int $academicRecordId)
    {
        DB::beginTransaction();
        try {
            $academicRecord = AcademicRecord::find($academicRecordId);
            $studentFee = $academicRecord->studentFee;
            $data['approved_date'] = Carbon::now();
            $data['approved_by'] = Auth::id();
            $data['submitted_date'] = Carbon::now();

            $previousBalance = $data['previous_balance'];

            //remove previous balance
            unset($data['previous_balance']);

            $studentFee->update($data);
            $initialFee = Config::get('constants.billing_type.INITIAL_FEE');
            $billing = $studentFee->billings()->updateOrCreate(['billing_type_id' => $initialFee],
            [
                'billing_status_id' => Config::get('constants.billing_status.UNPAID'),
                'billing_type_id' => $initialFee,
                'due_date' => Carbon::now()->addDays(7),
                'academic_record_id' => $academicRecordId,
                // 'school_year_id' => $academicRecord->school_year_id,
                // 'semester_id' => $academicRecord->semester_id,
                'student_id' => $academicRecord->student_id,
                'total_amount' => $studentFee->enrollment_fee,
                'previous_balance' => $previousBalance
            ]);

            $billing->billingItems()->updateOrCreate(['item' => 'Registration Fee'],
            [
                'amount' => $studentFee->enrollment_fee,
                'item' => 'Registration Fee'
            ]);

            $billing->update([
                'billing_no' => 'BN-' . date('Y') . '-' . str_pad($billing->id, 7, '0', STR_PAD_LEFT)
            ]);

            $billing->payments()->updateOrCreate(['billing_id' => $billing->id],
            [
                'school_year_id' => $academicRecord->school_year_id,
                'student_id' => $academicRecord->student_id,
                'payment_status_id' => Config::get('constants.payment_status.DRAFT'),
                'payment_mode_id' => Config::get('constants.payment_mode.BANK')
            ]);

            $studentFee->recomputeTerms();

            $items = [];
            foreach ($fees as $fee)
            {
                $items[$fee['school_fee_id']] = [
                    'amount' => $fee['amount'],
                    'is_initial_fee' => $fee['is_initial_fee'],
                    'notes' => $fee['notes']
                ];
            }

            $studentFee->studentFeeItems()->sync($items);

            $student = $academicRecord->student;

            if ($student && $student->is_onboarding) {
                $payments = Config::get('constants.onboarding_step.PAYMENTS');
                $student->update([
                    'onboarding_step_id' => $payments
                ]);
            }

            $assessmentApprovedStatus = Config::get('constants.academic_record_status.ASSESSMENT_APPROVED');
            $academicRecord->update([
                'academic_record_status_id' => $assessmentApprovedStatus
            ]);

            DB::commit();
            return $academicRecord;
        } catch (Exception $e) {
            DB::rollBack();
            Log::info('Error occured during AcademicRecordService approveAssessment method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function rejectAssessment(array $data, int $academicRecordId)
    {
        DB::beginTransaction();
        try {
            $academicRecord = AcademicRecord::find($academicRecordId);
            $assessmentRejectedStatus = Config::get('constants.academic_record_status.ASSESSMENT_REJECTED');
            $academicRecord->update([
                'academic_record_status_id' => $assessmentRejectedStatus
            ]);

            $studentFee = $academicRecord->studentFee;
            $data['disapproved_date'] = Carbon::now();
            $data['disapproved_by'] = Auth::id();
            $studentFee->update($data);
            DB::commit();
            return $academicRecord;
        } catch (Exception $e) {
            DB::rollBack();
            Log::info('Error occured during AcademicRecordService rejectAssessment method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function generateBilling(array $data, array $otherFees, int $academicRecordId)
    {
        DB::beginTransaction();
        try {
            $academicRecord = AcademicRecord::find($academicRecordId);
            $studentFee = $academicRecord->studentFee;
            $enrolledStatus = Config::get('constants.academic_record_status.ENROLLED');

            if ($academicRecord->academic_record_status_id !== $enrolledStatus) {
                throw ValidationException::withMessages([
                    'non_field_error' => ['Academic record is not enrolled yet.']
                ]);
            }

            if (!$academicRecord->schoolYear->is_active) {
                throw ValidationException::withMessages([
                    'non_field_error' => ['Academic record is not enrolled in current active school year.']
                ]);
            }

            $soaBillingType = Config::get('constants.billing_type.SOA');
            $otherBillingType = Config::get('constants.billing_type.BILL');
            if ($data['billing_type_id'] === $otherBillingType && !$otherFees) {
                throw ValidationException::withMessages([
                    'non_field_error' => ['Other Fees must have atleast one item.']
                ]);
            }

            $unpaidStatus = Config::get('constants.billing_status.UNPAID');
            $partiallyPaidStatus = Config::get('constants.billing_status.PARTIALLY_PAID');
            $data = Arr::add($data, 'student_fee_id', $studentFee->id);
            $data = Arr::add($data, 'billing_status_id', $unpaidStatus);
            $data = Arr::add($data, 'student_id', $academicRecord->student_id);
            $amount = Arr::pull($data, 'amount');
            $totalAmount = $data['billing_type_id'] === $soaBillingType ? collect($otherFees)->sum('amount') + $amount : collect($otherFees)->sum('amount');
            $data = Arr::add($data, 'total_amount', $totalAmount);
            $billing = $academicRecord->billings()->create($data);
            $billing->update([
                'billing_no' => 'BN-' . date('Y') . '-' . str_pad($billing->id, 7, '0', STR_PAD_LEFT)
            ]);


            if ($billing->billing_type_id === $soaBillingType) {
                $billing->studentFee()->first()->terms()->wherePivot('term_id', $billing->term_id)
                    ->update(['is_billed' => 1]);
                $billing->billingItems()->create([
                    'term_id' => $data['term_id'],
                    'amount' => $amount
                ]);

                $soaBillings = Billing::where('billing_type_id', $soaBillingType)
                    ->where('student_fee_id', $billing->studentFee->id)
                    ->whereIn('billing_status_id', [$unpaidStatus, $partiallyPaidStatus])
                    ->where('id', '!=', $billing->id)
                    ->where('is_forwarded', 0)
                    ->get();

                foreach ($soaBillings as $soaBilling) {
                    $soaBilling->update([
                        'is_forwarded' => 1,
                        'system_notes' => 'Balance forwarded to ' . $billing['billing_no'] .' on '.Carbon::now()->format('F d, Y') . ' amounting to ' . number_format($soaBilling->total_remaining_due, 2)
                    ]);
                }
            }


            foreach ($otherFees as $item) {
                $billing->billingItems()->create([
                    'amount' => $item['amount'],
                    'school_fee_id' => $item['school_fee_id']
                ]);
            }

            DB::commit();
            return $academicRecord;
        } catch (Exception $e) {
            DB::rollBack();
            Log::info('Error occured during AcademicRecordService generateBilling method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getAcademicRecordsOfSectionAndSubject(int $sectionId, int $subjectId, bool $isPaginated, int $perPage, array $filters)
    {
        try {
            $query = AcademicRecord::with([
                'student', 'level', 'course', 'semester', 'studentGrades' => function ($q) use ($sectionId, $subjectId) {
                    return $q->with('grades')
                        ->where('section_id', $sectionId)
                        ->where('subject_id', $subjectId)
                        ->get();
                }
            ])
                ->select('academic_records.*');

            $enrolledStatus = Config::get('constants.academic_record_status.ENROLLED');
            $query->where('academic_record_status_id', $enrolledStatus)->latest()->limit(1)
                ->whereHas('subjects', function ($q) use ($sectionId, $subjectId) {
                    $q->when($sectionId, function ($q) use ($sectionId) {
                        return $q->where('section_id', $sectionId);
                    })->when(
                        $subjectId,
                        function ($q) use ($subjectId) {
                            return $q->where('subject_id', $subjectId);
                        }
                    );
            });

            $criteria = $filters['criteria'] ?? false;
            $query->when($criteria, function ($query) use ($criteria) {
                $query->whereLike($criteria);
            });

            $orderBy = 'id';
            $sort = 'DESC';

            $ordering = $filters['ordering'] ?? false;
            if ($ordering) {
                $isDesc = str_starts_with($ordering, '-');
                $orderBy = $isDesc ? substr($ordering, 1) : $ordering;
                $sort = $isDesc ? 'DESC' : 'ASC';
            }
            $studentFields = ['complete_address', 'city', 'barangay', 'region'];

            if (in_array($orderBy, $studentFields)) {
                $query->orderByStudent($orderBy, $sort);
            } else {
                $query->orderBy($orderBy, $sort);
            }


            $academicRecords = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();

            return $academicRecords;
        } catch (Exception $e) {
            Log::info('Error occured during AcademicRecordService getAcademicRecordsOfSectionAndSubject method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
