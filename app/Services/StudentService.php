<?php

namespace App\Services;

use Image;
use Exception;
use App\Student;
use App\SchoolYear;
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
            $transcriptStatusId = 1;
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
                ])->transcript()->create([
                  'school_year_id' => $activeSchoolYear['id'], // active_school_year_id
                  'student_id' => $student->id,
                  'student_category_id' => $studentCategoryId,
                  'transcript_status_id' => $transcriptStatusId
                ]);

                $student->evaluation()->create([
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
                  ])->transcript()->create([
                    'school_year_id' => $activeSchoolYear['id'], // active_school_year_id
                    'student_id' => $student->id,
                    'student_category_id' => $studentCategoryId,
                    'transcript_status_id' => $transcriptStatusId
                  ]);

                  $student->evaluation()->create([
                    'student_id' => $student->id,
                    'student_category_id' => $studentCategoryId,
                    'evaluation_status_id' => $evaluationStatusId
                  ]);
                } else {
                  $student->admission()->create([
                    'school_year_id' =>  $activeSchoolYear['id'], // active_school_year_id
                    'admission_step_id' => 1,
                    'application_status_id' => 2
                  ])->transcript()->create([
                    'school_year_id' => $activeSchoolYear['id'], // active_school_year_id
                    'student_id' => $student->id,
                    'student_category_id' => $studentCategoryId,
                    'transcript_status_id' => $transcriptStatusId
                  ]);
                  $student->evaluation()->create([
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

}