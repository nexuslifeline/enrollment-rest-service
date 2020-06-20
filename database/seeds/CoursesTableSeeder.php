<?php

use App\Course;
use Illuminate\Database\Seeder;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'BSTM', 'description' => 'Bachelor of Science in Tourism Management', 'degree_type_id' => 2],
            ['name' => 'BSHRM', 'description' => 'Bachelor of Science in Hotel and Restaurant Management', 'degree_type_id' => 2],
            ['name' => 'BSED', 'description' => 'Bachelor of Secondary Education', 'degree_type_id' => 2],
            ['name' => 'BSIT', 'description' => 'Bachelor of Science in Information Technology', 'degree_type_id' => 2],
            ['name' => 'BSBA', 'description' => 'Bachelor of Science in Business Administration', 'degree_type_id' => 2],
            ['name' => 'BSC', 'description' => 'Bachelor of Science in Criminology', 'degree_type_id' => 2],
            ['name' => 'BA ENG LAN', 'description' => 'Bachelor of Arts in English Language Studies', 'degree_type_id' => 2],
            ['name' => 'BSA', 'description' => 'Bachelor of Science in Accountancy', 'degree_type_id' => 3],
            ['name' => 'BS MATH', 'description' => 'Bachelor of Science in Mathematics', 'degree_type_id' => 2],
            ['name' => 'BSAIS', 'description' => 'Bachelor of Science in Accounting Information System', 'degree_type_id' => 2], 
            ['name' => 'BSAT', 'description' => 'Bachelor of Science in Accounting Technology', 'degree_type_id' => 2],
            ['name' => 'BEED', 'description' => 'Bachelor of Elementary Education', 'degree_type_id' => 2],
            ['name' => 'BSPA', 'description' => 'Bachelor of Science in Public Affairs', 'degree_type_id' => 2],
            ['name' => 'BSOA', 'description' => 'Bachelor of Science in Office Administration', 'degree_type_id' => 2]
        ];

        $level_courses = collect([
            ['course_id' => 1, 'level_id' => 15, 'school_category_id' => 5],
            ['course_id' => 1, 'level_id' => 16, 'school_category_id' => 5],
            ['course_id' => 1, 'level_id' => 17, 'school_category_id' => 5],
            ['course_id' => 1, 'level_id' => 18, 'school_category_id' => 5],
            ['course_id' => 2, 'level_id' => 15, 'school_category_id' => 5],
            ['course_id' => 2, 'level_id' => 16, 'school_category_id' => 5],
            ['course_id' => 2, 'level_id' => 17, 'school_category_id' => 5],
            ['course_id' => 2, 'level_id' => 18, 'school_category_id' => 5],
            ['course_id' => 3, 'level_id' => 15, 'school_category_id' => 5],
            ['course_id' => 3, 'level_id' => 16, 'school_category_id' => 5],
            ['course_id' => 3, 'level_id' => 17, 'school_category_id' => 5],
            ['course_id' => 3, 'level_id' => 18, 'school_category_id' => 5],
            ['course_id' => 4, 'level_id' => 15, 'school_category_id' => 5],
            ['course_id' => 4, 'level_id' => 16, 'school_category_id' => 5],
            ['course_id' => 4, 'level_id' => 17, 'school_category_id' => 5],
            ['course_id' => 4, 'level_id' => 18, 'school_category_id' => 5],
            ['course_id' => 5, 'level_id' => 15, 'school_category_id' => 5],
            ['course_id' => 5, 'level_id' => 16, 'school_category_id' => 5],
            ['course_id' => 5, 'level_id' => 17, 'school_category_id' => 5],
            ['course_id' => 5, 'level_id' => 18, 'school_category_id' => 5],
            ['course_id' => 6, 'level_id' => 15, 'school_category_id' => 5],
            ['course_id' => 6, 'level_id' => 16, 'school_category_id' => 5],
            ['course_id' => 6, 'level_id' => 17, 'school_category_id' => 5],
            ['course_id' => 6, 'level_id' => 18, 'school_category_id' => 5],
            ['course_id' => 7, 'level_id' => 15, 'school_category_id' => 5],
            ['course_id' => 7, 'level_id' => 16, 'school_category_id' => 5],
            ['course_id' => 7, 'level_id' => 17, 'school_category_id' => 5],
            ['course_id' => 7, 'level_id' => 18, 'school_category_id' => 5],
            ['course_id' => 8, 'level_id' => 15, 'school_category_id' => 5],
            ['course_id' => 8, 'level_id' => 16, 'school_category_id' => 5],
            ['course_id' => 8, 'level_id' => 17, 'school_category_id' => 5],
            ['course_id' => 8, 'level_id' => 18, 'school_category_id' => 5],
            ['course_id' => 8, 'level_id' => 19, 'school_category_id' => 5],
            ['course_id' => 9, 'level_id' => 15, 'school_category_id' => 5],
            ['course_id' => 9, 'level_id' => 16, 'school_category_id' => 5],
            ['course_id' => 9, 'level_id' => 17, 'school_category_id' => 5],
            ['course_id' => 9, 'level_id' => 18, 'school_category_id' => 5],
            ['course_id' => 10, 'level_id' => 15, 'school_category_id' => 5],
            ['course_id' => 10, 'level_id' => 16, 'school_category_id' => 5],
            ['course_id' => 10, 'level_id' => 17, 'school_category_id' => 5],
            ['course_id' => 10, 'level_id' => 18, 'school_category_id' => 5],
            ['course_id' => 11, 'level_id' => 15, 'school_category_id' => 5],
            ['course_id' => 11, 'level_id' => 16, 'school_category_id' => 5],
            ['course_id' => 11, 'level_id' => 17, 'school_category_id' => 5],
            ['course_id' => 11, 'level_id' => 18, 'school_category_id' => 5],
            ['course_id' => 12, 'level_id' => 15, 'school_category_id' => 5],
            ['course_id' => 12, 'level_id' => 16, 'school_category_id' => 5],
            ['course_id' => 12, 'level_id' => 17, 'school_category_id' => 5],
            ['course_id' => 12, 'level_id' => 18, 'school_category_id' => 5],
            ['course_id' => 13, 'level_id' => 15, 'school_category_id' => 5],
            ['course_id' => 13, 'level_id' => 16, 'school_category_id' => 5],
            ['course_id' => 13, 'level_id' => 17, 'school_category_id' => 5],
            ['course_id' => 13, 'level_id' => 18, 'school_category_id' => 5],
            ['course_id' => 14, 'level_id' => 15, 'school_category_id' => 5],
            ['course_id' => 14, 'level_id' => 16, 'school_category_id' => 5],
            ['course_id' => 14, 'level_id' => 17, 'school_category_id' => 5],
            ['course_id' => 14, 'level_id' => 18, 'school_category_id' => 5]
        ]);

        foreach($data as $item){
          $course = Course::create($item);
          $levels = $level_courses->where('course_id', $course->id);
          $course->levels()->sync($levels);
        }
    }
}
