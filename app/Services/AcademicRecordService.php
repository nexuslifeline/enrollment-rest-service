<?php

namespace App\Services;

use Auth;
use Exception;
use App\Student;
use Carbon\Carbon;
use App\Evaluation;
use App\AcademicRecord;
use App\Billing;
use App\Level;
use App\Payment;
use App\SchoolYear;
use App\Semester;
use App\TranscriptRecord;
use App\Services\BillingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

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
                    $query->with(['address', 'photo', 'user']);
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
                return $query->whereIn('academic_record_status_id', $academicRecordStatusId);
            });

            // not equals to academicRecord status
            // $notAcademicRecordStatusId = $filters['not_academic_record_status_id'] ?? false;
            // $query->when($notAcademicRecordStatusId, function ($query) use ($notAcademicRecordStatusId) {
            //     return $query->where('academic_record_status_id', '!=', $notAcademicRecordStatusId);
            // });

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
                'transcriptRecord',
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
                }
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

            if (!$studentId) {
                throw new Exception('Student id not found!');
            }

            if (!$schoolCategoryId) {
                throw new Exception('School Category id not found!');
            }

            if (!$schoolYearId) {
                throw new Exception('School Year id not found!');
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
                'is_manual' => 1
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
                'curriculum',
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

    public function getPendingApprovalCount(array $filters)
    {
        $schoolYearId = $filters['school_year_id'] ?? false;
        $evaluationApprovedStatus = Config::get('constants.academic_record_status.EVALUATION_APPROVED');
        $enlistmentApprovedStatus = Config::get('constants.academic_record_status.ENLISTMENT_APPROVED');
        $draft = Config::get('constants.academic_record_status.DRAFT');
        $evaluationPending = Config::get('constants.academic_record_status.EVALUATION_PENDING');
        $evaluation = Evaluation::whereHas('academicRecord', function ($q) use ($draft, $evaluationPending) {
            return $q->whereIn('academic_record_status_id', [$draft, $evaluationPending]);
        })
        ->when($schoolYearId, function($q) use($schoolYearId) {
            return $q->whereHas('academicRecord', function ($query) use($schoolYearId) {
                return $query->where('school_year_id', $schoolYearId);
            });
        });

        $enlistment = AcademicRecord::where(function ($q) {
            return $q->whereHas('application', function ($query) {
                return $query->where('application_status_id', 4);
            })->orWhereHas('admission', function ($query) {
                return $query->where('application_status_id', 4);
            });
        })->where('academic_record_status_id', $evaluationApprovedStatus)
        ->when($schoolYearId, function($q) use($schoolYearId) {
            return $q->where('school_year_id', $schoolYearId);
        });


        $assessment = AcademicRecord::where(function ($q) {
            return $q->whereHas('application', function ($query) {
                return $query->where('application_status_id', 4);
            })->orWhereHas('admission', function ($query) {
                return $query->where('application_status_id', 4);
            });
        })->where('academic_record_status_id', $enlistmentApprovedStatus)
        ->when($schoolYearId, function($q) use($schoolYearId) {
            return $q->where('school_year_id', $schoolYearId);
        });

        $data['payment'] = Payment::where('payment_status_id', 4)->count();


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
            $subject = AcademicRecord::find($academicRecordId)
                ->subjects()
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
        $approvePaymentStatus = Config::get('constants.payment_status.APPROVED');
        // Note! update whereHas once academic_record_id id is added in billing table
        $billing = Billing::with(['payments', 'studentFee'])
            ->whereHas('studentFee', function ($q) use ($academicRecordId) {
                return $q->where('academic_record_id', $academicRecordId);
            })
            ->where('billing_type_id', $initialBillingType)
            ->first();
        // If there is payment, this means the initial billing has been fully paid since we dont accept payment less than its initial fee
        $billing->is_paid = $billing->payments &&
            $billing->payments->count() > 0 &&
            $billing->payments[0]->payment_status_id === $approvePaymentStatus;
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
            return $evaluation->load('academicRecord');
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

            DB::commit();
            return $academicRecord;
        } catch (Exception $e) {
            DB::rollBack();
            Log::info('Error occured during AcademicRecordService approveEnlistment method call: ');
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
            $studentFee->update($data);
            $initialFee = Config::get('constants.billing_type.INITIAL_FEE');
            $billing = $studentFee->billings()->updateOrCreate(['billing_type_id' => $initialFee],
            [
                'billing_status_id' => Config::get('constants.billing_status.UNPAID'),
                'billing_type_id' => $initialFee,
                'due_date' => Carbon::now()->addDays(7),
                'school_year_id' => $academicRecord->school_year_id,
                'semester_id' => $academicRecord->semester_id,
                'student_id' => $academicRecord->student_id,
                'total_amount' => $studentFee->enrollment_fee
            ]);

            $billing->billingItems()->create([
                'amount' => $studentFee->enrollment_fee,
                'item' => 'Registration Fee'
            ]);

            $billing->update([
                'billing_no' => 'BILL-' . date('Y') . '-' . str_pad($billing->id, 7, '0', STR_PAD_LEFT)
            ]);

            $billing->payments()->updateOrCreate(['billing_id' => $billing->id],
            [
                'school_year_id' => $academicRecord->school_year_id,
                'student_id' => $academicRecord->student_id,
                'payment_status_id' => Config::get('constants.payment_status.DRAFT')
            ]);

            $studentFee->recomputeTerms();

            $items = [];
            foreach ($fees as $fee)
            {
                $items[$fee['school_fee_id']] = [
                    'amount' => $fee->amount,
                    'is_initial_fee' => $fee->is_initial_fee,
                    'notes' => $fee->notes
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
}
