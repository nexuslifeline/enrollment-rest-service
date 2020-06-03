<?php

use Illuminate\Database\Seeder;

class ApplicationStepsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('application_steps')->insert(
            [
                ['name' => 'Profile', 'description' => ''],
                ['name' => 'Address', 'description' => ''],
                ['name' => 'Family', 'description' => ''],
                ['name' => 'Education', 'description' => ''],
                ['name' => 'Academic Year - Application', 'description' => '']
            ]
        );
    }
}
