<?php

use Illuminate\Database\Seeder;

class DegreeTypeLevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('degree_type_levels')->insert(
            [
                //senior
                ['degree_type_id' => 1, 'level_id' => 13],
                ['degree_type_id' => 1, 'level_id' => 14],
                //associate
                ['degree_type_id' => 2, 'level_id' => 15],
                ['degree_type_id' => 2, 'level_id' => 16],
                ['degree_type_id' => 2, 'level_id' => 17],
                //bachelors 4 yrs
                ['degree_type_id' => 3, 'level_id' => 15],
                ['degree_type_id' => 3, 'level_id' => 16],
                ['degree_type_id' => 3, 'level_id' => 17],
                ['degree_type_id' => 3, 'level_id' => 18],
                //bachelors 5 yrs
                ['degree_type_id' => 4, 'level_id' => 15],
                ['degree_type_id' => 4, 'level_id' => 16],
                ['degree_type_id' => 4, 'level_id' => 17],
                ['degree_type_id' => 4, 'level_id' => 18],
                ['degree_type_id' => 4, 'level_id' => 19],
                //masters
                ['degree_type_id' => 5, 'level_id' => 20],
                //masters doctorate
                ['degree_type_id' => 6, 'level_id' => 21],
            ]
        );
    }
}
