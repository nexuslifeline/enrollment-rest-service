<?php

use App\Level;
use App\Course;
use App\Student;
use App\Subject;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createFakeStudentAccount();
        //$this->createFakeCourses();
        $this->createFakeSubjects();
        $this->createFakeLevelWithAttachSubjectAndCourses();
        // $this->createFakeSchoolFees();
        $this->createFakeRateSheets();
        $this->createFakeAdmissions();
        $this->createFakeApplications();
    }

    public function createFakeAdmissions()
    {
        $faker = Faker\Factory::create();
        $limit = 30;
        for ($i = 1; $i < $limit; $i++) {
            $date = $faker->dateTimeBetween('-3 years', 'now');
            DB::table('admissions')->insert([
                'student_id' => $i ,
                'school_year_id' => 1,
                'admission_step_id' => 1,
                'application_status_id' => 2,
                'applied_date' => $date->format('Y-m-d')
            ]);
        }
    }

    public function createFakeApplications()
    {
        $faker = Faker\Factory::create();
        $limit = 30;
        for ($i = 1; $i < $limit; $i++) {
            $date = $faker->dateTimeBetween('-2 years', 'now');
            DB::table('applications')->insert([
                'student_id' => $i + 30,
                'school_year_id' => 1,
                'application_step_id' => 1,
                'application_status_id' => 2,
                'applied_date' => $date->format('Y-m-d')
            ]);
        }
    }

    public function createFakeSchoolFees()
    {
        DB::table('school_fees')->insert([
            ['name' => 'Tuition Fee'],
            ['name' => 'Miscellaneous Fee'],
            ['name' => 'Basic Fee'],
            ['name' => 'Registration Fee'],
            ['name' => 'Computer Fee'],
            ['name' => 'Medical and Dental Fee'],
            ['name' => 'Application Fee']
        ]);
    }

    public function createFakeRateSheets()
    {
        for ($i = 1; $i < 21; $i++) {
            $course_id = rand(1, 5);
            DB::table('rate_sheets')->insert([
                'level_id' => $i,
                'course_id' => $i > 12 ? $course_id : null
            ]);

            for ($x = 1; $x < 7; $x++) {
                $amount = rand(5, 10) * 100;
                DB::table('rate_sheet_fees')->insert([
                    'rate_sheet_id' => $i,
                    'school_fee_id' => $x,
                    'amount' => $amount
                ]);
            }
        }

    }


    public function createFakeStudentAccount()
    {
        $faker = Faker\Factory::create();
        $limit = 100;
        for ($i = 0; $i < $limit; $i++) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $date = $faker->dateTimeBetween('-30 years', 'now');
            $email = strtolower(str_replace('', ' ', $firstName . $lastName . '@nexuslifeline.com'));

            $student = Student::create([
                'name' => $firstName . ' ' . $lastName,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'birth_date' => $date->format('Y-m-d')
            ]);

            $student->user()->create([
                'username' => $email,
                'password' => bcrypt('password')
            ]);

            if ($i < 50) { // atleast half of student have address
                $student->address()->create([
                    'current_complete_address' => $faker->address
                ]);
            }
        }
    }

    public function createFakeCourses()
    {
        $courses = [
            ['name' => 'BSIT', 'description' => 'Bachelor of Science in Information Technology'],
            ['name' => 'BSCE', 'description' => 'Bachelor of Science in Computer Engineering'],
            ['name' => 'BSA', 'description' => 'Bachelor of Science in Accountancy'],
            ['name' => 'BSED', 'description' => 'Bachelor in Secondary Education'],
            ['name' => 'BEED', 'description' => 'Bachelor in Elementary Education'],
            ['name' => 'BSMT', 'description' => 'Bachelor of Science in Marine Transportation'],
            ['name' => 'AB Broadcasting', 'description' => 'Bachelor of Arts in Broadcasting'],
            ['name' => 'AB Journalism', 'description' => 'Bachelor of Arts in Journalism']
        ];

        foreach($courses as $course) {
            Course::create($course);
        }

    }

    public function createFakeSubjects()
    {
        $faker = Faker\Factory::create();
        $limit = 100;
        for ($i = 0; $i < $limit; $i++) {
            $code = $faker->word;
            $name = $faker->unique()->word;
            $description = $faker->sentence;
            $labs = rand(1, 3);
            $units = rand(1, 6);
            $amount_per_unit = rand(3, 8) * 20;
            $amount_per_lab = rand(3, 8) * 20;
            $subject = Subject::create([
                'code' => $code,
                'name' => $name,
                'description' => $description,
                'labs' => $labs,
                'units' => $units,
                'amount_per_unit' => $amount_per_unit,
                'amount_per_lab' => $amount_per_lab,
                'total_amount' => $amount_per_unit + $amount_per_lab,
            ]);
        }
    }

    public function createFakeLevelWithAttachSubjectAndCourses()
    {
        $levels = [
            ['name' => 'Kinder 1', 'school_category_id' => 1],
            ['name' => 'Kinder 2', 'school_category_id' => 1],
            ['name' => 'Grade 1', 'school_category_id' => 2],
            ['name' => 'Grade 2', 'school_category_id' => 2],
            ['name' => 'Grade 3', 'school_category_id' => 2],
            ['name' => 'Grade 4', 'school_category_id' => 2],
            ['name' => 'Grade 5', 'school_category_id' => 2],
            ['name' => 'Grade 6', 'school_category_id' => 2],
            ['name' => 'Grade 7', 'school_category_id' => 3],
            ['name' => 'Grade 8', 'school_category_id' => 3],
            ['name' => 'Grade 9', 'school_category_id' => 3],
            ['name' => 'Grade 10', 'school_category_id' => 3],
            ['name' => 'Grade 11', 'school_category_id' => 4],
            ['name' => 'Grade 12', 'school_category_id' => 4],
            ['name' => 'First Year College', 'school_category_id' => 5],
            ['name' => 'Second Year College', 'school_category_id' => 5],
            ['name' => 'Third Year College', 'school_category_id' => 5],
            ['name' => 'Fourth Year College', 'school_category_id' => 5],
            ['name' => 'Fifth Year College', 'school_category_id' => 5],
            ['name' => 'Grad School 1', 'school_category_id' => 6],
            ['name' => 'Grad School 2', 'school_category_id' => 6],
            ['name' => 'Grad School 3', 'school_category_id' => 6],
            ['name' => 'Grad School 4', 'school_category_id' => 6],
        ];

        foreach($levels as $level) {
            $resource = Level::create($level);
            if ($level['school_category_id'] > 4) {
                $course_id = rand(1, 5);
            }

            $attach = []; $max = rand(8, 20); // randomize number of subjects
            for ($i = 0; $i < $max; $i++) {
                $subject_id = rand(1, 100); // random id of subjects
                $semester_id = rand(1, 2);
                $attach[$subject_id] = [
                    'course_id' => $course_id ?? null,
                    'school_category_id' => $level['school_category_id'],
                    'semester_id' => ($course_id ?? false) ? $semester_id : null
                ];
            }
            $resource->subjects()->attach($attach);
        }
    }
}
