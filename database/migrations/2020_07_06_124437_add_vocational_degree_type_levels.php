<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVocationalDegreeTypeLevels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('degree_type_levels')->insert(
            [
                //vocational degree
                ['degree_type_id' => 7, 'level_id' => 22],
            ]
        );
    }

    
}
