<?php

use Illuminate\Database\Seeder;

class PermissionGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permission_groups')->insert([
            ['id' => 1, 'name' => 'Department Management', 'description' => ''],
            ['id' => 2, 'name' => 'Semester Management', 'description' => ''],
            ['id' => 3, 'name' => 'School Year Management', 'description' => ''],
            ['id' => 4, 'name' => 'School Category Management', 'description' => ''],
            ['id' => 5, 'name' => 'User Group Management', 'description' => ''],
            ['id' => 6, 'name' => 'Student Management', 'description' => ''],
            ['id' => 7, 'name' => 'Personnel Management', 'description' => ''],
            ['id' => 8, 'name' => 'Rate Sheet Management', 'description' => ''],
            ['id' => 9, 'name' => 'Fee Category Management', 'description' => ''],
            ['id' => 10, 'name' => 'School Fee Management', 'description' => ''],
            ['id' => 11, 'name' => 'Curriculum Management', 'description' => ''],
            ['id' => 12, 'name' => 'Section & Schedule Management', 'description' => ''],
            ['id' => 13, 'name' => 'Subject Management', 'description' => ''],
            ['id' => 14, 'name' => 'Course Management', 'description' => ''],
            ['id' => 15, 'name' => 'Evaluation & Admission Approval', 'description' => ''],
            ['id' => 16, 'name' => 'Student Subject Approval', 'description' => ''],
            ['id' => 17, 'name' => 'Student Fee Approval', 'description' => ''],
            ['id' => 18, 'name' => 'Student Payment Approval', 'description' => ''],
        ]);
    }
}
