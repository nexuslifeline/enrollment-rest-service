<?php

namespace App\Services;

use Image;
use Exception;
use App\Billing;
use App\Payment;
use App\Student;
use App\Admission;
use Carbon\Carbon;
use App\Evaluation;
use App\SchoolYear;
use App\Application;
use App\AcademicRecord;
use App\TranscriptRecord;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class StudentService
{

    public function register(array $data)
    {
        DB::beginTransaction();
        try {
            // Note! this should be move in Config/Constants
            $academicRecordStatusId = 1;
            // $evaluationStatusId = 1;
            $transcriptRecordStatusId = 1; //1 = draft
            // $isEnrolled = $data['is_enrolled'];
            $isAdmission = $data['is_admission'] ?? 0;

            $activeSchoolYear = SchoolYear::where('is_active', 1)->first();
            if (!$activeSchoolYear) {
                throw ValidationException::withMessages([
                    'non_field_error' => ['No Active SY found!']
                ]);
            }

            $student = Student::create([
                'student_no' => $data['student_no'],
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'],
                'last_name' => $data['last_name'],
                'mobile_no' => $data['mobile_no'],
                'email' => $data['username'],
                'civil_status_id' => Config::get('constants.civil_status.SINGLE'),
                'is_onboarding' => 1
            ]);

            $transcriptRecord = $student->transcriptRecords()->create([
                'student_id' => $student->id,
                'transcript_record_status_id' => $transcriptRecordStatusId
            ]);


            // $evaluation = $student->evaluations()->create([
            //     'student_id' => $student->id,
            //     // 'student_category_id' => $studentCategoryId, //remove on evaluation table 5/22021
            //     // 'evaluation_status_id' => $evaluationStatusId,
            //     // 'transcript_record_id' => $transcriptRecord->id
            // ]);

            // student category
            // 1 - new
            // 2 - old
            // 3 - transferee
            $studentCategoryId = $data['student_category_id'];

            $academicRecord = $student->academicRecords()->create([
                'school_year_id' => $activeSchoolYear['id'], // active_school_year_id
                'student_id' => $student->id,
                'student_category_id' => $studentCategoryId,
                'academic_record_status_id' => $academicRecordStatusId,
                'transcript_record_id' => $transcriptRecord->id,
                'is_admission' => $isAdmission,
                'student_type_id' => Config::get('constants.student_type.REGULAR')
            ]);

            $academicRecord->application()->create([
                // 'is_admission' => $isAdmission
            ]);
            $academicRecord->evaluation()->create([]);

            $academicRecord->studentFee()->create([]);

            // if ($isEnrolled) {
            //     //
            //     $student->applications()->create([
            //         // 'school_year_id' =>  $activeSchoolYear['id'], // active_school_year_id
            //         'is_admission' => $isAdmission
            //         // 'application_step_id' => 1,
            //         // 'application_status_id' => 2
            //     ])->academicRecord()->create([
            //         'school_year_id' => $activeSchoolYear['id'], // active_school_year_id
            //         'student_id' => $student->id,
            //         'student_category_id' => $studentCategoryId,
            //         'academic_record_status_id' => $academicRecordStatusId,
            //         'transcript_record_id' => $transcriptRecord->id,
            //         'evaluation_id' =>  $evaluation->id
            //     ]);
            // } else {
            //     if ($studentCategoryId === 2) {

            //         $student->applications()->create([
            //             // 'school_year_id' =>  $activeSchoolYear['id'], // active_school_year_id
            //             'is_admission' => $isAdmission
            //             // 'application_step_id' => 1,
            //             // 'application_status_id' => 2
            //         ])->academicRecord()->create([
            //             'school_year_id' => $activeSchoolYear['id'], // active_school_year_id
            //             'student_id' => $student->id,
            //             'student_category_id' => $studentCategoryId,
            //             'academic_record_status_id' => $academicRecordStatusId,
            //             'transcript_record_id' => $transcriptRecord->id,
            //             'evaluation_id' =>  $evaluation->id
            //         ]);


            //     } else {
            //         // if ($studentCategoryId === 2) {

            //             $student->applications()->create([
            //                 // 'school_year_id' =>  $activeSchoolYear['id'], // active_school_year_id
            //                 'is_admission' => $isAdmission
            //                 // 'application_step_id' => 1,
            //                 // 'application_status_id' => 2
            //             ])->academicRecord()->create([
            //                 'school_year_id' => $activeSchoolYear['id'], // active_school_year_id
            //                 'student_id' => $student->id,
            //                 'student_category_id' => $studentCategoryId,
            //                 'academic_record_status_id' => $academicRecordStatusId,
            //                 'transcript_record_id' => $transcriptRecord->id,
            //                 'evaluation_id' =>  $evaluation->id
            //             ]);

            //         // } else {
            //         //     if ($studentCategoryId === 2) {

            //         //         $student->applications()->create([
            //         //             'school_year_id' =>  $activeSchoolYear['id'], // active_school_year_id
            //         //             'is_admission' => $isAdmission
            //         //             // 'application_step_id' => 1,
            //         //             // 'application_status_id' => 2
            //         //         ])->academicRecord()->create([
            //         //             'school_year_id' => $activeSchoolYear['id'], // active_school_year_id
            //         //             'student_id' => $student->id,
            //         //             'student_category_id' => $studentCategoryId,
            //         //             'academic_record_status_id' => $academicRecordStatusId,
            //         //             'transcript_record_id' => $transcriptRecord->id,
            //         //             'evaluation_id' =>  $evaluation->id
            //         //         ]);

            //         //     } else {

            //         //         $student->admission()->create([
            //         //             'school_year_id' =>  $activeSchoolYear['id'], // active_school_year_id
            //         //             'admission_step_id' => 1,
            //         //             // 'application_status_id' => 2
            //         //         ])->academicRecord()->create([
            //         //             'school_year_id' => $activeSchoolYear['id'], // active_school_year_id
            //         //             'student_id' => $student->id,
            //         //             'student_category_id' => $studentCategoryId,
            //         //             'academic_record_status_id' => $academicRecordStatusId,
            //         //             'transcript_record_id' => $transcriptRecord->id,
            //         //             'evaluation_id' =>  $evaluation->id
            //         //         ]);
            //         //     }
            //         // }
            //     }
            // }
            $user = $student->user()->create([
                'username' => $data['username'],
                'password' => Hash::make($data['password'])
            ]);

            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during StudentService register method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function manualRegister(array $data)
    {
        DB::beginTransaction();
        try {
            $studentId = $data['id'] ?? null;
            // $isManual = $studentId ? 1 : 0; // set is_manual for manually registered
            $academicRecord = $data['academic_record'] ?? false;
            $academicRecordSubjects = $data['academic_record_subjects'] ?? false;
            $user = $data['user'] ?? false;
            $evaluation = $data['evaluation'] ?? false;
            $transcriptRecord = $data['transcript_record'] ?? false;
            $transcriptSubjects = $data['transcript_record_subjects'] ?? false;
            $studentFee = $data['student_fee'] ?? false;
            $application = $data['application'] ?? false;

            $student = Student::updateOrCreate(['id' => $studentId], [
                'birth_date' => $data['birth_date'],
                'civil_status_id' => $data['civil_status_id'],
                'email' => $data['email'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'middle_name' => $data['middle_name'],
                'mobile_no' => $data['mobile_no']
                // 'is_manual' =>  $isManual
            ]);

            if ($academicRecord) {
                $activeAcademicRecord = $student->academicRecords()->updateOrCreate(['id' => $academicRecord['id']], $academicRecord);
                if ($academicRecordSubjects) {
                    $items = [];
                    foreach ($academicRecordSubjects as $subject) {
                        $items[$subject['subject_id']] = [
                            'section_id' => $subject['section_id'],
                        ];
                    }
                    $activeAcademicRecord->subjects()->sync($items);
                }
                if ($studentFee) {
                    $student->studentFees()
                        ->updateOrCreate(['academic_record_id' => $activeAcademicRecord->id], $studentFee);
                }

                if ($application) {
                    $activeApplication = $student->applications()->create([
                        'applied_date' => Carbon::now(),
                        // 'school_year_id' => $application['school_year_id'],
                        // 'application_status_id' => $application['application_status_id'],
                        // 'application_step_id' => $application['application_step_id'],
                        'approved_by' => Auth::user()->id,
                        'approved_date' => Carbon::now()
                    ]);

                    $activeAcademicRecord->update(['application_id' => $activeApplication->id]);
                }
            }

            //disabled for adjustment on transcript record 5/15/2021
            // if ($transcriptRecord) {
            //     $schoolCategoryId = $academicRecord['school_category_id'];
            //     if ($schoolCategoryId === 4 || $schoolCategoryId === 5) {
            //         $activeTranscript = $student->transcriptRecords()
            //             ->where('school_category_id', $schoolCategoryId)
            //             ->where('course_id', $academicRecord['course_id'])
            //             ->where('transcript_record_status_id', 1)
            //             ->first();
            //         if ($activeTranscript) {
            //             $activeTranscript->update($transcriptRecord);
            //             $transcript = $activeTranscript;
            //         } else {
            //             $transcript = $student->transcriptRecords()->updateOrCreate(['id' => $transcriptRecord['id']], $transcriptRecord);
            //         }
            //     } else {
            //         $transcript = $student->transcriptRecords()->updateOrCreate(['id' => $transcriptRecord['id']], $transcriptRecord);
            //     }
            //     if ($transcriptSubjects) {
            //         $items = [];
            //         foreach ($transcriptSubjects as $subject) {
            //             $items[$subject['subject_id']] = [
            //                 'level_id' => $subject['level_id'],
            //                 'semester_id' => $subject['semester_id'],
            //                 'is_taken' => $subject['is_taken'],
            //                 'grade' => $subject['grade'],
            //                 'notes' => $subject['notes']
            //             ];
            //         }
            //         $transcript->subjects()->sync($items);
            //     }
            //     if ($evaluation) {
            //         $student->evaluations()->updateOrCreate(['id' => $evaluation['id']], [
            //             'student_id' => $student->id,
            //             'student_curriculum_id' => $evaluation['student_curriculum_id'],
            //             'curriculum_id' => $evaluation['curriculum_id'],
            //             'student_category_id' => $evaluation['student_category_id'],
            //             'school_category_id' => $evaluation['school_category_id'],
            //             'level_id' => $evaluation['level_id'],
            //             'semester_id' => $evaluation['semester_id'],
            //             'course_id' => $evaluation['course_id'],
            //             'evaluation_status_id' => $evaluation['evaluation_status_id'],
            //             // 'transcript_record_id' => $transcript->id,
            //             'school_year_id' => $evaluation['school_year_id'],
            //         ]);
            //     }
            // }


            if ($user) {
                $student->user()->create([
                    'username' => $user['username'],
                    'password' => Hash::make($user['password'])
                ]);
            }

            DB::commit();
            $student->load(['user']);
            $student->append('active_transcript_record', 'evaluation');
            return $student;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during StudentService register method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function list(bool $isPaginated, int $perPage, array $filters)
    {
        try {
            $query = Student::with(['address', 'family', 'education', 'photo', 'user', 'latestAcademicRecord'])
            ->select('students.*');

            $sectionId = $filters['section_id'] ?? false;
            $levelId = $filters['level_id'] ?? false;
            $courseId = $filters['course_id'] ?? false;
            $semesterId = $filters['semester_id'] ?? false;
            $subjectId = $filters['subject_id'] ?? false;
            $withTheSubject = $filters['with_the_subject'] ?? false;
            $isDropped = Arr::exists($filters, 'is_dropped') == 1 ? $filters['is_dropped'] : false;
            // return $isDropped;
            $enrolledStatus = Config::get('constants.academic_record_status.ENROLLED');
            $query->when($levelId || $courseId || $semesterId || $sectionId || $subjectId, function ($q) use ($levelId, $courseId, $semesterId, $sectionId, $subjectId, $withTheSubject, $isDropped, $enrolledStatus, $filters) {
                return $q->whereHas('academicRecords', function ($query) use ($levelId, $courseId, $semesterId, $sectionId, $subjectId, $withTheSubject, $isDropped, $enrolledStatus, $filters) {
                    return $query->where('academic_record_status_id', $enrolledStatus)->latest()->limit(1)
                    ->when($levelId, function ($q) use ($levelId) {
                        return $q->where('level_id', $levelId);
                    })
                    ->when($courseId, function ($q) use ($courseId) {
                        return $q->where('course_id', $courseId);
                    })
                    ->when($semesterId, function ($q) use ($semesterId) {
                        return $q->where('semester_id', $semesterId);
                    })
                    ->whereHas('subjects', function ($q) use ($sectionId, $subjectId, $isDropped, $filters) {
                        $q->when($sectionId, function ($q) use ($sectionId) {
                            return $q->where('section_id', $sectionId);
                        })->when($subjectId, function ($q) use ($subjectId) {
                            return $q->where('subject_id', $subjectId);
                        })->when(Arr::exists($filters, 'is_dropped') == 1, function ($q) use ($isDropped) {
                            return $q->where('is_dropped', $isDropped);
                        });
                    });
                });
            });

            $query->when($withTheSubject && $subjectId, function ($q) use ($subjectId, $enrolledStatus) {
                return $q->with(['academicRecords' => function ($q) use ($subjectId, $enrolledStatus) {
                    return $q->where('academic_record_status_id', $enrolledStatus)
                    ->latest()
                        ->with(['subjects' => function ($q) use ($subjectId) {
                            return $q->where('subject_id', $subjectId);
                        }]);
                }]);
            });

            $isOnboarding = Arr::exists($filters, 'is_onboarding') == 1 ? $filters['is_onboarding'] : false;
            $query->when(Arr::exists($filters, 'is_onboarding') == 1, function ($q) use ($isOnboarding) {
                return $q->where('is_onboarding', $isOnboarding);
            });

            $criteria = $filters['criteria'] ?? false;
            $query->when($criteria, function ($query) use ($criteria) {
                // return $query->where(function ($q) use ($criteria) {
                //     return $q->where('name', 'like', '%' . $criteria . '%')
                //         ->orWhere('first_name', 'like', '%' . $criteria . '%')
                //         ->orWhere('middle_name', 'like', '%' . $criteria . '%')
                //         ->orWhere('student_no', 'like', '%' . $criteria . '%')
                //         ->orWhere('last_name', 'like', '%' . $criteria . '%')
                //         ->orWhere('email', 'like', '%' . $criteria . '%');;

                // });

                //scopedWhereLike on student model
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


            $students = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();

            foreach($students as $student) {
                if ($student->latestAcademicRecord) {
                    $student->latestAcademicRecord->load(['level', 'course', 'semester']);
                }
            }
            $students->append(['has_open_academic_record']);

            return $students;
        } catch (Exception $e) {
            Log::info('Error occured during StudentService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $student = Student::find($id)->makeVisible(['created_at', 'updated_at']);
            $student->load([
                'address',
                'family',
                'education',
                'photo',
                'user',
                'requirements',
                'files',
                'latestAcademicRecord'
            ]);
            // $student->append('latest_academic_record', 'has_open_academic_record');
            $student->latestAcademicRecord->load([
                'level',
                'course',
                'semester',
                'evaluation' => function ($q) {
                    return $q->with('lastSchoolLevel');
                },
                'application',
                'section',
                'studentType',
                'schoolCategory',
                'schoolYear',
                'transcriptRecord'
            ]);
            $student->latestAcademicRecord->append(['has_initial_billing']);

            return $student;
        } catch (Exception $e) {
            Log::info('Error occured during StudentService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(array $data, array $studentInfo, array $related)
    {
        DB::beginTransaction();
        try {
            $student = Student::create($data);
            foreach ($related as $item) {
                $info = $studentInfo[$item] ?? false;
                if ($info) {
                    $student->{$item}()->updateOrCreate(['student_id' => $student->id], $studentInfo[$item]);
                }
            }

            $student->load($related);
            DB::commit();
            return $student;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during StudentService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, array $studentInfo, array $related, int $id)
    {
        DB::beginTransaction();
        try {
            $student = Student::find($id);
            $student->update($data);

            // $activeApplication = $studentInfo['active_application'] ?? false;
            // if ($activeApplication) {
            //     $application = Application::find($activeApplication['id']);
            //     if ($application) {
            //         $application->update($activeApplication);
            //     }
            // }

            // $activeAdmission = $studentInfo['active_admission'] ?? false;
            // if ($activeAdmission) {
            //     $admission = Admission::find($activeAdmission['id']);
            //     if ($admission) {
            //         $admission->update($activeAdmission);
            //     }
            // }

            //disabled for adjustment on transcript record 5/15/2021
            // $activeTranscriptRecord = $studentInfo['active_transcript_record'] ?? false;
            // if ($activeTranscriptRecord) {
            //     $transcriptRecord = TranscriptRecord::find($activeTranscriptRecord['id']);
            //     if ($transcriptRecord) {
            //         $transcriptRecord->update($activeTranscriptRecord);
            //     }
            // }

            //to be handled in academic record model ?
            $activeAcademicRecord = $studentInfo['academic_record'] ?? false;
            if ($activeAcademicRecord) {
                $academicRecord = AcademicRecord::find($activeAcademicRecord['id']);
                if ($academicRecord) {
                    $academicRecord->update($activeAcademicRecord);

                    //update transcript record school category
                    $academicRecord->transcriptRecord()->update([
                        'school_category_id' => $academicRecord->school_category_id
                    ]);

                    $subjects = $studentInfo['subjects'] ?? false;
                    if ($subjects) {
                        $academicRecord->subjects()->sync($subjects);
                    }
                }
            }

            $activeEvaluation = $studentInfo['active_evaluation'] ?? false;
            if ($activeEvaluation) {
                $evaluation = Evaluation::find($activeEvaluation['id']);
                if ($evaluation) {
                    $evaluation->update($activeEvaluation);
                }
            }

            if (isset($studentInfo['requirements'])) {
                $requirements = $studentInfo['requirements'] ?? [];
                $items = [];
                foreach ($requirements as $requirement) {
                    $items[$requirement['requirement_id']] = [
                        'school_category_id' => $requirement['school_category_id']
                    ];
                }
                $student->requirements()->wherePivot('school_category_id', $student->latestAcademicRecord->school_category_id)->sync($items);
            }

            foreach ($related as $item) {
                $info = $studentInfo[$item] ?? false;
                if ($info) {
                    $student->{$item}()->updateOrCreate(['student_id' => $student->id], $studentInfo[$item]);
                    // $student->active_application->update
                }
            }

            $user = $studentInfo['user'] ?? false;
            if ($user) {

                if(array_key_exists('username', $user) && !array_key_exists('password', $user)) {
                    //username only
                    $student->user()->updateOrCreate(
                        [
                            'userable_id' => $student->id
                        ],
                        [
                            'username' => $user['username'],
                        ]
                    );
                }
                elseif(!array_key_exists('username', $user) && array_key_exists('password', $user)) {
                    //password only
                    $student->user()->updateOrCreate(
                        [
                            'userable_id' => $student->id
                        ],
                        [
                            'password' => Hash::make($user['password'])
                        ]
                    );
                }
                else {
                    $student->user()->updateOrCreate(
                        [
                            'userable_id' => $student->id
                        ],
                        [
                            'username' => $user['username'],
                            'password' => Hash::make($user['password'])
                        ]
                    );
                }
            }

            $student->load([
                'address',
                'family',
                'education',
                'photo',
                'user',
                'latestAcademicRecord'
            ])->fresh();
            $student->latestAcademicRecord->load([
                'level',
                'course',
                'semester',
                'evaluation' => function ($q) {
                    return $q->with('lastSchoolLevel');
                },
                'application',
                'section',
                'studentType',
                'schoolCategory',
                'schoolYear',
                'transcriptRecord'
            ]);
            $student->latestAcademicRecord->append(['has_initial_billing']);
            $student->append(['has_open_academic_record']);
            DB::commit();
            return $student;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during StudentService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        try {
            $student = Student::find($id);
            $student->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during StudentService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getBillingsOfStudent(array $filters, int $id)
    {
        try {
            //soa billing
            $billingTypeId = $filters['billing_type_id'] ?? false;
            $billingStatusId = $filters['billing_status_id'] ?? false;
            $billingStatusPaid = Config::get('constants.billing_status.PAID');

            $billings = Billing::where('student_id', $id)
                ->where('is_forwarded', 0)
                ->where('billing_status_id', '!=', $billingStatusPaid)
                ->when($billingStatusId, function ($q) use ($billingStatusId) {
                    return $q->where('billing_status_id', $billingStatusId);
                })
                ->when($billingTypeId, function ($q) use ($billingTypeId) {
                    return $q->where('billing_type_id', $billingTypeId);
                })
                ->get();
            $billings->append(['total_paid', 'submitted_payments']);
            return $billings->sortBy('due_date');
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during StudentService getBillingsOfStudent method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getAllBillingsOfStudent(bool $isPaginated, int $perPage, array $filters, int $id)
    {
        try {
            //soa billing
            $billingTypeId = $filters['billing_type_id'] ?? false;
            // $showAll = $filters['show_all'] ?? false;
            $billingStatusId = $filters['billing_status_id'] ?? false;
            $isForwarded = $filters['is_forwarded'] ?? false;
            $criteria = $filters['criteria'] ?? false;

            $query = Billing::with([
                'billingItems',
                'term',
                'academicRecord' => function($q) {
                    return $q->with(['schoolYear', 'level', 'semester']);
                }
            ])->where('student_id', $id);
            //billing type id
            $query->when($billingTypeId, function ($q) use ($billingTypeId) {
                if (!is_array($billingTypeId)) {
                    return $q->where('billing_type_id', $billingTypeId);
                } else {
                    return $q->whereIn('billing_type_id', $billingTypeId);
                }
            });
            //billing status id
            $query->when($billingStatusId, function ($q) use ($billingStatusId) {
                return $q->where('billing_status_id', $billingStatusId);
            });
            //is forwarded
            $query->when($isForwarded, function ($q) use ($isForwarded) {
                return $q->where('is_forwarded', $isForwarded);
            });

            //criteria or search
            $query->when($criteria, function ($q) use ($criteria) {
                return $q->where('billing_no', 'like', '%' . $criteria . '%')
                    ->orWhere('system_notes', 'like', '%' . $criteria . '%')
                    ->orWhereHas('term', function ($q) use ($criteria) {
                        return $q->where('name', 'like', '%' . $criteria . '%');
                    });
            });

            $orderBy = 'id';
            $sort = 'DESC';

            $ordering = $filters['ordering'] ?? false;
            if ($ordering) {
                $isDesc = str_starts_with($ordering, '-');
                $orderBy = $isDesc ? substr($ordering, 1) : $ordering;
                $sort = $isDesc ? 'DESC' : 'ASC';
            }

            $billingStatusFields = ['billing_status_name'];
            $billingTypeFields = ['billing_type_name'];

            if (in_array($orderBy, $billingStatusFields)) {
                $query->orderByBillingStatus($orderBy, $sort);
            } else if (in_array($orderBy, $billingTypeFields)) {
                $query->orderByBillingType($orderBy, $sort);
            } else {
                $query->orderBy($orderBy, $sort);
            }

            $billings = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();
            $billings->append(['total_paid', 'submitted_payments','pending_payments_count']);
            return $billings;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during StudentService getAllBillingsOfStudent method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function enroll(array $data, array $studentInfo, array $related, int $id)
    {

        DB::beginTransaction();

        try {
            // Note! should be moved in constants
            $academicRecordStatusId = 1; // draft
            // $evaluationStatusId = 1; // pending
            $transcriptRecordStatusId = 1; //1 = draft
            $studentCategoryId = 2; //old
            $isManual = 1;

            $activeSchoolYear = SchoolYear::where('is_active', 1)->first();
            if (!$activeSchoolYear) {
                throw new Exception('No active school year found!');
            }

            $student = Student::find($id);
            // return $student;

            $activeApplication = $studentInfo['active_application'] ?? false;
            if ($activeApplication &&  is_null($activeApplication['id'])) {

                //do if no active application
                $student->applications()->create([
                    // 'school_year_id' =>  $activeSchoolYear['id'],
                    // 'application_step_id' =>  $activeApplication['application_step_id'],
                    // 'application_status_id' =>  $activeApplication['application_status_id'],
                    // 'is_manual' =>  $isManual
                ])->academicRecord()->create([
                    'school_year_id' => $activeSchoolYear['id'], // active_school_year_id
                    'student_id' => $student->id,
                    'student_category_id' => $studentCategoryId,
                    'academic_record_status_id' => $academicRecordStatusId
                ]);

                $activeEvaluation = $studentInfo['evaluation'] ?? false;
                if ($activeEvaluation) {
                    $schoolCategoryId = $activeEvaluation['school_category_id'];
                    $levelId =  $activeEvaluation['level_id'];
                    $courseId = $activeEvaluation['course_id'];

                    $transcriptRecord = TranscriptRecord::where('student_id', $student->id)
                        ->where('school_category_id', $schoolCategoryId)
                        ->where('level_id', in_array($schoolCategoryId, [4, 5, 6, 7]) ? null : $levelId)
                        ->where('course_id', $courseId)->first();

                    if ($transcriptRecord && in_array($schoolCategoryId, [4, 5, 6, 7])) {
                        //transcript exist therefor use existing transcript and create evaluation
                        // $activeEvaluation->transcript_record_id = $transcriptRecord['id'];
                        // $activeEvaluation->student_curriculum_id = $transcriptRecord['student_curriculum_id'];
                        // $activeEvaluation->curriculum_id = $transcriptRecord['curriculum_id'];
                        $student->create([
                            'student_category_id' => $activeEvaluation['student_category_id'],
                            'student_curriculum_id' => $transcriptRecord->student_curriculum_id,
                            'curriculum_id' => $transcriptRecord->curriculum_id,
                            'school_year_id' => $activeEvaluation['school_year_id'],
                            'level_id' => $activeEvaluation['level_id'],
                            'course_id' => $activeEvaluation['course_id'],
                            'semester_id' => $activeEvaluation['semester_id'],
                            'school_category_id' => $activeEvaluation['school_category_id'],
                            'evaluation_status_id' => $activeEvaluation['evaluation_status_id'],
                            'submitted_date' => $activeEvaluation['submitted_date']
                        ]);
                    } else {
                        $transcriptRecord = $student->transcriptRecords()->create([
                            'school_category_id' => $schoolCategoryId,
                            'level_id' => in_array($schoolCategoryId, [4, 5, 6, 7]) ? null : $levelId,
                            'course_id' => $courseId,
                            'transcript_record_status_id' => $transcriptRecordStatusId
                        ]);


                        // $activeEvaluation['student_curriculum_id'] = $transcriptRecord['student_curriculum_id'];
                        // $activeEvaluation['curriculum_id'] = $transcriptRecord['curriculum_id'];
                        $student->evaluations()->create([
                            'student_category_id' => $activeEvaluation['student_category_id'],
                            'transcript_record_id' => $transcriptRecord['id'],
                            'school_category_id' => $activeEvaluation['school_category_id'],
                            'school_year_id' => $activeEvaluation['school_year_id'],
                            'level_id' => $activeEvaluation['level_id'],
                            'semester_id' => $activeEvaluation['semester_id'],
                            'course_id' => $activeEvaluation['course_id'],
                            'submitted_date' => $activeEvaluation['submitted_date'],
                            'evaluation_status_id' => 2 // evaluation status - submitted
                        ]);
                    }
                }
            } else {
                //do if no active application exists
                $application = Application::find($activeApplication['id']);
                if ($application) {
                    $application->update($activeApplication);
                }

                //disabled for adjustment on transcript record 5/15/2021
                // $activeTranscriptRecord = $studentInfo['active_transcript_record'] ?? false;
                // if ($activeTranscriptRecord) {
                //     $transcriptRecord = TranscriptRecord::find($activeTranscriptRecord['id']);
                //     if ($transcriptRecord) {
                //         $transcriptRecord->update($activeTranscriptRecord);
                //     }
                // }

                $activeAcademicRecord = $studentInfo['academic_record'] ?? false;
                if ($activeAcademicRecord) {
                    $academicRecord = AcademicRecord::find($activeAcademicRecord['id']);
                    if ($academicRecord) {
                        $academicRecord->update($activeAcademicRecord);
                        $subjects = $studentInfo['subjects'] ?? false;
                        if ($subjects) {
                            $academicRecord->subjects()->sync($subjects);
                        }
                    }
                }

                $activeEvaluation = $studentInfo['evaluation'] ?? false;
                if ($activeEvaluation) {
                    $evaluation = Evaluation::find($activeEvaluation['id']);
                    if ($evaluation) {
                        $evaluation->update($activeEvaluation);
                    }
                }
            }


            $student->load(['address', 'family', 'education', 'photo', 'user',])->fresh();
            $student->append(['active_application', 'academic_record', 'active_transcript_record', 'evaluation']);
            DB::commit();
            return $student;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during StudentService enroll method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getLedgerOfStudent($studentId, $schoolYearId, $asOfDate)
    {
        try {
            DB::statement(DB::raw('set @bal=0;'));

            $billings = Billing::select(
                [
                    'billing_no as reference',
                    'billing_types.name as txn_type',
                    DB::raw('0 as credit'),
                    DB::raw('(total_amount) as debit'),
                    'billings.created_at as txn_date'
                ]
            )
                ->join('billing_types', 'billings.billing_type_id', '=', 'billing_types.id')
                ->where('student_id', $studentId)
                ->whereDate('billings.created_at', '<=', $asOfDate);

            $billings->when($schoolYearId, function ($q) use ($schoolYearId) {
                return $q->whereHas('studentFee', function ($q) use ($schoolYearId) {
                    return $q->whereHas('academicRecord', function ($q) use ($schoolYearId) {
                        return $q->where('school_year_id', $schoolYearId);
                    });
                });
            });

            $payments = Payment::select(
                [
                    'reference_no as reference',
                    DB::raw("'Payment' as txn_type"),
                    'amount as credit',
                    DB::raw('0 as debit'),
                    'created_at as txn_date'
                ]
            )->where('student_id', $studentId)
                ->where('payment_status_id', '=', 2) //added filter payment status = approved
                ->whereDate('created_at', '<=', $asOfDate);

            $payments->when($schoolYearId, function ($q) use ($schoolYearId) {
                return $q->where('school_year_id', $schoolYearId);
            });

            $billingPayments = $billings->with('billingItems')->union($payments)->orderBy('txn_date');

            $result = DB::table(function ($query) use ($billingPayments) {
                $query->select('*', DB::raw('(@bal := @bal + (debit - credit)) as balance'))
                    ->from($billingPayments);
            })->get();

            return $result;

        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during StudentService getLedgerOfStudent method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getAcademicRecords(int $studentId, bool $isPaginated, int $perPage, array $filters)
    {
        try {
            $query = AcademicRecord::with(['schoolYear', 'schoolCategory', 'level', 'course', 'semester', 'section'])
                ->where('student_id', $studentId);

            $academicRecord = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();

            return $academicRecord;
        } catch (Exception $e) {
            Log::info('Error occured during StudentService getAcademicRecords method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getTotalRemainingBalance(int $studentId)
    {
        try {
            // initial billing must include prev balance
            $initialFee = Config::get('constants.billing_type.INITIAL_FEE');
            $initialBillings = Billing::where('student_id', $studentId)
                ->where('billing_type_id', $initialFee)
                ->get();

            $initialBillings->append(['total_paid']);

            $billings = Billing::where('student_id', $studentId)
                ->where('billing_type_id', '!=', $initialFee)
                ->get();

            $initialBillingBalance = ($initialBillings->sum('total_amount') + $initialBillings->sum('previous_balance')) - $initialBillings->sum('total_paid');
            $billingBalance = $billings->sum('total_amount') - $billings->sum('total_paid');
            $totalRemainingBalance = $initialBillingBalance + $billingBalance;

            return $totalRemainingBalance;
        } catch (Exception $e) {
            Log::info('Error occured during StudentService getTotalRemainingBalance method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getPaidBillingsOverpay(int $studentId)
    {
        try {
            $paid = Config::get('constants.billing_status.PAID');
            // $partial = Config::get('constants.billing_status.PARTIALLY_PAID');
            $billings = Billing::where('student_id', $studentId)
                ->where('billing_status_id', $paid)
                ->get();

            $billings->append(['total_overpay']);

            $overpay = max($billings->sum('total_overpay'), 0);
            return $overpay;
        } catch (Exception $e) {
            Log::info('Error occured during StudentService getOverpay method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function lastPaymentReference(int $studentId)
    {
        try{
            $payment = Payment::where('student_id', $studentId)
                ->where('overpay', '>', 0)
                ->where('is_overpay_forwarded', 0)
                ->latest()
                ->first();
            return $payment ? $payment->reference_no : '';
        } catch (Exception $e) {
            Log::info('Error occured during StudentService getOverpay method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    // public function getPendingPaymentsCount(int $studentId)
    // {
    //     try {
    //         $pending = Config::get('constants.payment_status.PENDING');
    //         $paymentsCount = Payment::where('student_id', $studentId)
    //             ->where('payment_status_id', $pending)
    //             ->count();

    //         return $paymentsCount;
    //     } catch (Exception $e) {
    //         Log::info('Error occured during StudentService getPendingPaymentsCount method call: ');
    //         Log::info($e->getMessage());
    //         throw $e;
    //     }
    // }

    public function getStudentOfSectionAndSubject(int $sectionId, int $subjectId, bool $isPaginated, int $perPage, array $filters)
    {
        try {
            $query = Student::with(['address', 'family', 'education', 'photo', 'user'
            ])
            ->select('students.*');

            $enrolledStatus = Config::get('constants.academic_record_status.ENROLLED');
            $query->whereHas('academicRecords', function ($query) use ($sectionId, $subjectId, $enrolledStatus) {
                return $query->where('academic_record_status_id', $enrolledStatus)->latest()->limit(1)
                    ->whereHas('subjects', function ($q) use ($sectionId, $subjectId) {
                        $q->when($sectionId, function ($q) use ($sectionId) {
                            return $q->where('section_id', $sectionId);
                        })->when($subjectId,
                            function ($q) use ($subjectId) {
                                return $q->where('subject_id', $subjectId);
                            }
                        );
                    });
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


            $students = $isPaginated
            ? $query->paginate($perPage)
                : $query->get();

            foreach ($students as $student) {
                if ($student->latestAcademicRecord) {
                    $student->latestAcademicRecord->load(['level', 'course', 'semester']);
                }
            }

            $students->append(['has_open_academic_record']);

            return $students;
        } catch (Exception $e) {
            Log::info('Error occured during StudentService getStudentOfSectionAndSubject method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

}
