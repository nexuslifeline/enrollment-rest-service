<?php

use App\SchoolYear;
use Illuminate\Database\Seeder;

class SchoolYearsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SchoolYear::create([
            'name' => 'SY 2020-2021',
            'description' => '',
            'start_date' => '2020-08-24',
            'is_active' => 1
        ]);
    }
}
