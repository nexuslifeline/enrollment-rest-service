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
        $this->createFakeCourses();
        $this->createFakeSubjects();
        $this->createFakeLevelWithAttachSubjectAndCourses();
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
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'birth_date' => $date->format('Y-m-d')
            ]);

            $student->user()->create([
                'username' => $email,
                'password' => bcrypt('password')
            ]);
        }
    }

    public function createFakeCourses()
    {
        $courses = [
            ['name' => 'BSIT', 'description' => ''],
            ['name' => 'COMENG', 'description' => ''],
            ['name' => 'HRM', 'description' => ''],
            ['name' => 'COMSCI', 'description' => ''],
            ['name' => 'CHS', 'description' => '']
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
            $name = $faker->unique()->word;
            $description = $faker->sentence;
            $subject = Subject::create([
                'name' => $name,
                'description' => $description
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
            ['name' => 'First Year College', 'school_category_id' => 4],
            ['name' => 'Second Year College', 'school_category_id' => 4],
            ['name' => 'Third Year College', 'school_category_id' => 4],
            ['name' => 'Fourth Year College', 'school_category_id' => 4],
            ['name' => 'Fifth Year College', 'school_category_id' => 4],
            ['name' => 'Grad School 1', 'school_category_id' => 5],
            ['name' => 'Grad School 2', 'school_category_id' => 5],
            ['name' => 'Grad School 3', 'school_category_id' => 5],
            ['name' => 'Grad School 4', 'school_category_id' => 5],
        ];

        foreach($levels as $level) {
            $resource = Level::create($level);
            if ($level['school_category_id'] > 3) {
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
