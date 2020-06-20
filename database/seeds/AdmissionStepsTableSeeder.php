<?php

use Illuminate\Database\Seeder;

class AdmissionStepsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admission_steps')->insert(
            [
                ['name' => 'Profile', 'description' => ''],
                ['name' => 'Address', 'description' => ''],
                ['name' => 'Family', 'description' => ''],
                ['name' => 'Education', 'description' => ''],
                ['name' => 'Academic Year - Application', 'description' => ''], // application for current SY level, course, semester, subjects, etc,
                                                                                // same with the old student application
                ['name' => 'Requirements', 'description' => ''],
                ['name' => 'Status', 'description' => ''],
                ['name' => 'Payments', 'description' => ''],
                ['name' => 'Waiting', 'description' => '']
            ]
        );
    }
}
