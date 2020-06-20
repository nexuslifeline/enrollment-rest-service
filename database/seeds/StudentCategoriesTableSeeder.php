<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('student_categories')->insert(
            [
                ['name' => 'New Student', 'description' => 'New Student, Transfereers, Cross Enrollees'],
                ['name' => 'Old Student', 'description' => 'Old Student only']
            ]
        );
    }
}
