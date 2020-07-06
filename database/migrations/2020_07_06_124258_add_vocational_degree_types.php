<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVocationalDegreeTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('degree_types')->insert(
            [
              ['name' => 'Vocational', 'description' => 'Vocational'],
            ]
        );
    }

   
}
