<?php

use Illuminate\Database\Seeder;

class DegreeTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('degree_types')->insert(
        [
          ['name' => 'Senior High', 'description' => 'Senior High'],
          ['name' => 'Associate Degree', 'description' => 'Associate Degree'],
          ['name' => 'Bachelors Degree(4 yrs)', 'description' => 'Bachelors Degree(4 yrs)'],
          ['name' => 'Bachelors Degree(5 yrs)', 'description' => 'Bachelors Degree(5 yrs)'],
          ['name' => 'Masters Degree', 'description' => 'Masters Degree'],
          ['name' => 'Doctorate Degree', 'description' => 'Doctorate Degree'],
        ]
      );
    }
}
