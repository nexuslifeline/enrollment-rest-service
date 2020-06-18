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
                ['degree_type_id' => 1, 'level_id' => 15],
                ['degree_type_id' => 1, 'level_id' => 16],
                ['degree_type_id' => 1, 'level_id' => 17],
                ['degree_type_id' => 2, 'level_id' => 15],
                ['degree_type_id' => 2, 'level_id' => 16],
                ['degree_type_id' => 2, 'level_id' => 17],
                ['degree_type_id' => 2, 'level_id' => 18],
                ['degree_type_id' => 3, 'level_id' => 15],
                ['degree_type_id' => 3, 'level_id' => 16],
                ['degree_type_id' => 3, 'level_id' => 17],
                ['degree_type_id' => 3, 'level_id' => 18],
                ['degree_type_id' => 3, 'level_id' => 19],
                ['degree_type_id' => 4, 'level_id' => 20],
                ['degree_type_id' => 4, 'level_id' => 21],
                ['degree_type_id' => 5, 'level_id' => 22],
                ['degree_type_id' => 5, 'level_id' => 23],
                ['degree_type_id' => 5, 'level_id' => 24],
                ['degree_type_id' => 5, 'level_id' => 25],
                ['degree_type_id' => 5, 'level_id' => 26],
                ['degree_type_id' => 5, 'level_id' => 27],
                ['degree_type_id' => 5, 'level_id' => 28],
            ]
        );
    }
}
