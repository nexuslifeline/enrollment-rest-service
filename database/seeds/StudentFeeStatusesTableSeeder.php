<?php

use Illuminate\Database\Seeder;

class StudentFeeStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('student_fee_statuses')->insert(
            [
                ['name' => 'Draft/Pending', 'description' => 'Draft/Pending'],
                ['name' => 'Approved', 'description' => 'Approved'],
            ]
        );
    }
}
