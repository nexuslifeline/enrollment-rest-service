<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertDataOnLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('levels')->insert(
            [
                ['name' => 'Short Term Program', 'description' => 'Short Term Program', 'school_category_id' => 7]
            ]
        );
    }
}
