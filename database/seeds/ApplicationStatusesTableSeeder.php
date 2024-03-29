<?php

use Illuminate\Database\Seeder;

class ApplicationStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('application_statuses')->insert(
            [
                ['name' => 'Approved', 'description' => ''],
                ['name' => 'Draft', 'description' => 'Draft/Pending'],
                ['name' => 'Rejected', 'description' => ''],
                ['name' => 'Submitted', 'description' => ''],
                ['name' => 'Approved Assesment', 'description' => ''],
                ['name' => 'Payment Submitted', 'description' => ''],
                ['name' => 'Completed', 'description' => ''],
            ]
        );
    }
}
