<?php

use Illuminate\Database\Seeder;

class LevelsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('levels')->insert(
      [
        ['name' => 'Kinder 1', 'school_category_id' => 1],
        ['name' => 'Kinder 2', 'school_category_id' => 1],
        ['name' => 'Grade 1', 'school_category_id' => 2],
        ['name' => 'Grade 2', 'school_category_id' => 2],
        ['name' => 'Grade 3', 'school_category_id' => 2],
        ['name' => 'Grade 4', 'school_category_id' => 2],
        ['name' => 'Grade 5', 'school_category_id' => 2],
        ['name' => 'Grade 6', 'school_category_id' => 2],
        ['name' => 'Grade 7', 'school_category_id' => 3],
        ['name' => 'Grade 8', 'school_category_id' => 3],
        ['name' => 'Grade 9', 'school_category_id' => 3],
        ['name' => 'Grade 10', 'school_category_id' => 3],
        ['name' => 'Grade 11', 'school_category_id' => 4],
        ['name' => 'Grade 12', 'school_category_id' => 4],
        ['name' => 'First Year College', 'school_category_id' => 5],
        ['name' => 'Second Year College', 'school_category_id' => 5],
        ['name' => 'Third Year College', 'school_category_id' => 5],
        ['name' => 'Fourth Year College', 'school_category_id' => 5],
        ['name' => 'Fifth Year College', 'school_category_id' => 5],
        ['name' => 'Year 1 - Masters Degree', 'school_category_id' => 6],
        ['name' => 'Year 2 - Masters Degree', 'school_category_id' => 6],
        ['name' => 'Year 1 - Doctorate Degree', 'school_category_id' => 6],
        ['name' => 'Year 2 - Doctorate Degree', 'school_category_id' => 6],
        ['name' => 'Year 3 - Doctorate Degree', 'school_category_id' => 6],
        ['name' => 'Year 4 - Doctorate Degree', 'school_category_id' => 6],
        ['name' => 'Year 5 - Doctorate Degree', 'school_category_id' => 6],
        ['name' => 'Year 6 - Doctorate Degree', 'school_category_id' => 6],
        ['name' => 'Year 7 - Doctorate Degree', 'school_category_id' => 6],
      ]
    );
  }
}
