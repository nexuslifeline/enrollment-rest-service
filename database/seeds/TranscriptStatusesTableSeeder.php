<?php

use Illuminate\Database\Seeder;

class TranscriptStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('transcript_statuses')->insert(
            [
                ['name' => 'Draft', 'description' => 'Draft'],
                ['name' => 'Finalized', 'description' => 'Finalized'],
            ]
        );
    }
}
