<?php

use Illuminate\Database\Seeder;

class StudentTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('student_types')->insert(
            [
                ['name' => 'Regular', 'description' => ''],
                ['name' => 'Irregular', 'description' => '']
            ]
        );
    }
}
