<?php

namespace App\Services;

use Image;
use Exception;
use App\Billing;
use App\Student;
use App\Admission;
use Carbon\Carbon;
use App\Evaluation;
use App\SchoolYear;
use App\Application;
use App\AcademicRecord;
use App\TranscriptRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class StudentService
{

    public function register(array $data)
    {
        DB::beginTransaction();
        try {
            $academicRecordStatusId = 1;
            $evaluationStatusId = 1;
            $transcriptRecordStatusId = 1; //1 = draft
            $isEnrolled = $data['is_enrolled'];

            $activeSchoolYear = SchoolYear::where('is_active', 1)->first();
            if (!$activeSchoolYear) {
                throw new Exception('No active school year found!');
            }

            $student = Student::create([
                'student_no' => $data['student_no'],
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'],
                'last_name' => $data['last_name'],
                'mobile_no' => $data['mobile_no'],
                'email' => $data['username']
            ]);

            //create transcript record
            $transcriptRecord = $student->transcriptRecords()->create([
                'student_id' => $student->id,
                'transcript_record_status_id' => $transcriptRecordStatusId
            ]);


            // student category
            // 1 - new
            // 2 - old
            // 3 - transferee
            $studentCategoryId = $data['student_category_id'];

            if ($isEnrolled) {
                $student->applications()->create([
                    'school_year_id' =>  $activeSchoolYear['id'], // active_school_year_id
                    'application_step_id' => 1,
                    'application_status_id' => 2
                ])->academicRecord()->create([
                    'school_year_id' => $activeSchoolYear['id'], // active_school_year_id
                    'student_id' => $student->id,
                    'student_category_id' => $studentCategoryId,
                    'academic_record_status_id' => $academicRecordStatusId
                ]);

                $student->evaluations()->create([
                    'student_id' => $student->id,
                    'student_category_id' => $studentCategoryId,
                    'evaluation_status_id' => $evaluationStatusId,
                    'transcript_record_id' => $transcriptRecord->id
                ]);
            } else {
                if ($studentCategoryId === 2) {
                    $student->applications()->create([
                        'school_year_id' =>  $activeSchoolYear['id'], // active_school_year_id
                        'application_step_id' => 1,
                        'application_status_id' => 2
                    ])->academicRecord()->create([
                        'school_year_id' => $activeSchoolYear['id'], // active_school_year_id
                        'student_id' => $student->id,
                        'student_category_id' => $studentCategoryId,
                        'academic_record_status_id' => $academicRecordStatusId
                    ]);

                    $student->evaluations()->create([
                        'student_id' => $student->id,
                        'student_category_id' => $studentCategoryId,
                        'evaluation_status_id' => $evaluationStatusId,
                        'transcript_record_id' => $transcriptRecord->id
                    ]);
                } else {
                    if ($studentCategoryId === 2) {
                        $student->applications()->create([
                            'school_year_id' =>  $activeSchoolYear['id'], // active_school_year_id
                            'application_step_id' => 1,
                            'application_status_id' => 2
                        ])->academicRecord()->create([
                            'school_year_id' => $activeSchoolYear['id'], // active_school_year_id
                            'student_id' => $student->id,
                            'student_category_id' => $studentCategoryId,
                            'academic_record_status_id' => $academicRecordStatusId
                        ]);

                        $student->evaluations()->create([
                            'student_id' => $student->id,
                            'student_category_id' => $studentCategoryId,
                            'evaluation_status_id' => $evaluationStatusId
                        ]);
                    } else {
                        if ($studentCategoryId === 2) {
                            $student->applications()->create([
                                'school_year_id' =>  $activeSchoolYear['id'], // active_school_year_id
                                'application_step_id' => 1,
                                'application_status_id' => 2
                            ])->academicRecord()->create([
                                'school_year_id' => $activeSchoolYear['id'], // active_school_year_id
                                'student_id' => $student->id,
                                'student_category_id' => $studentCategoryId,
                                'academic_record_status_id' => $academicRecordStatusId
                            ]);

                            $student->evaluations()->create([
                                'student_id' => $student->id,
                                'student_category_id' => $studentCategoryId,
                                'evaluation_status_id' => $evaluationStatusId
                            ]);
                        } else {
                            $student->admission()->create([
                                'school_year_id' =>  $activeSchoolYear['id'], // active_school_year_id
                                'admission_step_id' => 1,
                                'application_status_id' => 2
                            ])->academicRecord()->create([
                                'school_year_id' => $activeSchoolYear['id'], // active_school_year_id
                                'student_id' => $student->id,
                                'student_category_id' => $studentCategoryId,
                                'academic_record_status_id' => $academicRecordStatusId
                            ]);

                            $student->evaluations()->create([
                                'student_id' => $student->id,
                                'student_category_id' => $studentCategoryId,
                                'evaluation_status_id' => $evaluationStatusId,
                                'transcript_record_id' => $transcriptRecord->id

                            ]);
                        }
                    }
                }
            }
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
                        'school_year_id' => $application['school_year_id'],
                        'application_status_id' => $application['application_status_id'],
                        'application_step_id' => $application['application_step_id'],
                        'approved_by' => Auth::user()->id,
                        'approved_date' => Carbon::now()
                    ]);

                    $activeAcademicRecord->update(['application_id' => $activeApplication->id]);
                }
            }

            if ($transcriptRecord) {
                $schoolCategoryId = $academicRecord['school_category_id'];
                if ($schoolCategoryId === 4 || $schoolCategoryId === 5) {
                    $activeTranscript = $student->transcriptRecords()
                        ->where('school_category_id', $schoolCategoryId)
                        ->where('course_id', $academicRecord['course_id'])
                        ->where('transcript_record_status_id', 1)
                        ->first();
                    if ($activeTranscript) {
                        $activeTranscript->update($transcriptRecord);
                        $transcript = $activeTranscript;
                    } else {
                        $transcript = $student->transcriptRecords()->updateOrCreate(['id' => $transcriptRecord['id']], $transcriptRecord);
                    }
                } else {
                    $transcript = $student->transcriptRecords()->updateOrCreate(['id' => $transcriptRecord['id']], $transcriptRecord);
                }
                if ($transcriptSubjects) {
                    $items = [];
                    foreach ($transcriptSubjects as $subject) {
                        $items[$subject['subject_id']] = [
                            'level_id' => $subject['level_id'],
                            'semester_id' => $subject['semester_id'],
                            'is_taken' => $subject['is_taken'],
                            'grade' => $subject['grade'],
                            'notes' => $subject['notes']
                        ];
                    }
                    $transcript->subjects()->sync($items);
                }
                if ($evaluation) {
                    $student->evaluations()->updateOrCreate(['id' => $evaluation['id']], [
                        'student_id' => $student->id,
                        'student_curriculum_id' => $evaluation['student_curriculum_id'],
                        'curriculum_id' => $evaluation['curriculum_id'],
                        'student_category_id' => $evaluation['student_category_id'],
                        'school_category_id' => $evaluation['school_category_id'],
                        'level_id' => $evaluation['level_id'],
                        'semester_id' => $evaluation['semester_id'],
                        'course_id' => $evaluation['course_id'],
                        'evaluation_status_id' => $evaluation['evaluation_status_id'],
                        'transcript_record_id' => $transcript->id
                    ]);
                }
            }


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
            $query = Student::with(['address', 'family', 'education', 'photo', 'user']);

            $criteria = $filters['criteria'] ?? false;
            $query->when($criteria, function ($query) use ($criteria) {
                return $query->where(function ($q) use ($criteria) {
                    return $q->where('name', 'like', '%' . $criteria . '%')
                        ->orWhere('first_name', 'like', '%' . $criteria . '%')
                        ->orWhere('middle_name', 'like', '%' . $criteria . '%')
                        ->orWhere('student_no', 'like', '%' . $criteria . '%')
                        ->orWhere('last_name', 'like', '%' . $criteria . '%');
                });
            });

            $students = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();

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
            $student = Student::find($id);
            $student->load(['address', 'family', 'education', 'photo', 'user']);
            $student->append('active_application', 'active_admission', 'academic_record', 'active_transcript_record', 'evaluation');
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

            $activeApplication = $studentInfo['active_application'] ?? false;
            if ($activeApplication) {
                $application = Application::find($activeApplication['id']);
                if ($application) {
                    $application->update($activeApplication);
                }
            }

            $activeAdmission = $studentInfo['active_admission'] ?? false;
            if ($activeAdmission) {
                $admission = Admission::find($activeAdmission['id']);
                if ($admission) {
                    $admission->update($activeAdmission);
                }
            }

            $activeTranscriptRecord = $studentInfo['active_transcript_record'] ?? false;
            if ($activeTranscriptRecord) {
                $transcriptRecord = TranscriptRecord::find($activeTranscriptRecord['id']);
                if ($transcriptRecord) {
                    $transcriptRecord->update($activeTranscriptRecord);
                }
            }

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

            foreach ($related as $item) {
                $info = $studentInfo[$item] ?? false;
                if ($info) {
                    $student->{$item}()->updateOrCreate(['student_id' => $student->id], $studentInfo[$item]);
                    // $student->active_application->update
                }
            }

            $user = $studentInfo['user'] ?? false;
            if ($user) {
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

            $student->load(['address', 'family', 'education', 'photo', 'user',])->fresh();
            $student->append(['active_admission', 'active_application', 'academic_record', 'active_transcript_record', 'evaluation']);
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

    public function getBillingsOfStudent($id)
    {
        try {
            //soa billing type id
            $billingTypeId = 2;
            $soaBillings = Billing::where('billing_type_id', $billingTypeId)
                ->where('student_id', $id)
                ->latest()
                ->take(1)
                ->get();

            $soaBillings->append('total_paid');

            //soa billing type id
            $billingTypeId = 1;
            $initialBilling = Billing::where('billing_type_id', $billingTypeId)
                ->where('student_id', $id)
                ->get();
            $initialBilling->append('total_paid');

            //other billing type id
            $billingTypeId = 3;
            $otherBillings = Billing::where('billing_type_id', $billingTypeId)
                ->where('student_id', $id)->get();
            $otherBillings->append('total_paid');


            $billings = $soaBillings->merge($initialBilling)->merge($otherBillings);

            return $billings->sortBy('due_date');
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during StudentService getBillingsOfStudent method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function enroll(array $data, array $studentInfo, array $related, int $id)
    {

        DB::beginTransaction();

        try {

            $academicRecordStatusId = 1; //pending
            $evaluationStatusId = 1; // pending
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
                    'school_year_id' =>  $activeSchoolYear['id'],
                    'application_step_id' =>  $activeApplication['application_step_id'],
                    'application_status_id' =>  $activeApplication['application_status_id'],
                    'is_manual' =>  $isManual
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
                        $transcriptRecord->evaluations()->create([
                            'student_category_id' => $activeEvaluation['student_category_id'],
                            'student_id' => $student->id,
                            'student_curriculum_id' => $transcriptRecord->student_curriculum_id,
                            'curriculum_id' => $transcriptRecord->curriculum_id,
                            'level_id' => $activeEvaluation['level_id'],
                            'course_id' => $activeEvaluation['course_id'],
                            'semester_id' => $activeEvaluation['semester_id'],
                            'school_category_id' => $activeEvaluation['school_category_id'],
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

                $activeTranscriptRecord = $studentInfo['active_transcript_record'] ?? false;
                if ($activeTranscriptRecord) {
                    $transcriptRecord = TranscriptRecord::find($activeTranscriptRecord['id']);
                    if ($transcriptRecord) {
                        $transcriptRecord->update($activeTranscriptRecord);
                    }
                }

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
            $student->append(['active_admission', 'active_application', 'academic_record', 'active_transcript_record', 'evaluation']);
            DB::commit();
            return $student;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during StudentService enroll method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
