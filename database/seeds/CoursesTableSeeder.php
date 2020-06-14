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
            ['name' => 'BSTM', 'description' => 'Bachelor of Science in Tourism Management'],
            ['name' => 'BSHRM', 'description' => 'Bachelor of Science in Hotel and Restaurant Management'],
            ['name' => 'BSED', 'description' => 'Bachelor of Secondary Education'],
            ['name' => 'BSIT', 'description' => 'Bachelor of Science in Information Technology'],
            ['name' => 'BSBA', 'description' => 'Bachelor of Science in Business Administration'],
            ['name' => 'BSC', 'description' => 'Bachelor of Science in Criminology'],
            ['name' => 'BA ENG LAN', 'description' => 'Bachelor of Arts in English Language Studies'],
            ['name' => 'BSA', 'description' => 'Bachelor of Science in Accountancy'],
            ['name' => 'BS MATH', 'description' => 'Bachelor of Science in Mathematics'],
            ['name' => 'BSAIS', 'description' => 'Bachelor of Science in Accounting Information System'], 
            ['name' => 'BSAT', 'description' => 'Bachelor of Science in Accounting Technology'],
            ['name' => 'BEED', 'description' => 'Bachelor of Elementary Education'],
            ['name' => 'BSPA', 'description' => 'Bachelor of Science in Public Affairs'],
            ['name' => 'BSOA', 'description' => 'Bachelor of Science in Office Administration']
        ];
        foreach($data as $item){
            Course::create($item);
        }
    }
}
