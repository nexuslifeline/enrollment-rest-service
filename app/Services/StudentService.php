<?php

namespace App\Services;

use Image;
use Exception;
use App\Billing;
use App\Student;
use App\Admission;
use App\SchoolYear;
use App\Application;
use App\AcademicRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
                    'evaluation_status_id' => $evaluationStatusId
                  ]);
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

    public function list(bool $isPaginated, int $perPage, array $filters)
    {
        try {
            $query = Student::with(['address', 'family', 'education', 'photo', 'user']);

            $criteria = $filters['criteria'] ?? false;
            $query->when($criteria, function($query) use ($criteria) {
                return $query->where(function($q) use ($criteria) {
                    return $q->where('name', 'like', '%'.$criteria.'%')
                    ->orWhere('first_name', 'like', '%'.$criteria.'%')
                    ->orWhere('middle_name', 'like', '%'.$criteria.'%')
                    ->orWhere('last_name', 'like', '%'.$criteria.'%');
                });
            });

            $students = $isPaginated
                ? $query->paginate($perPage)
                : $query->all();

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
            $student->load(['address', 'family', 'education', 'photo', 'evaluation']);
            $student->append('active_application', 'active_admission', 'academic_record');
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
            foreach($related as $item) {
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

            foreach($related as $item) {
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

            $student->load(['address', 'family', 'education','photo', 'user', 'evaluation'])->fresh();
            $student->append(['active_admission', 'active_application', 'academic_record']);
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

    public function getBillingsOfStudent($id) {
        try {
            //soa billing type id
            $billingTypeId = 2;
            $soaBillings = Billing::where('billing_type_id', $billingTypeId)
                        ->where('student_id', $id)->latest()->get();
            $soaBillings->append('total_paid');

            //other billing type id
            $billingTypeId = 3;
            $otherBillings = Billing::where('billing_type_id', $billingTypeId)
                    ->where('student_id', $id)->get();
            $otherBillings->append('total_paid');


            $billings = $soaBillings->merge($otherBillings);

            return $billings;

        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during StudentService getBillingsOfStudent method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}