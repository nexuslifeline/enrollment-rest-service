<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultDataOnDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('days')->insert(
            [
                ['name' => 'Monday', 'description' => 'Monday'],
                ['name' => 'Tuesday', 'description' => 'Tuesday'],
                ['name' => 'Wednesday', 'description' => 'Wednesday'],
                ['name' => 'Thursday', 'description' => 'Thursday'],
                ['name' => 'Friday', 'description' => 'Friday'],
                ['name' => 'Saturday', 'description' => 'Saturday'],
                ['name' => 'Sunday', 'description' => 'Sunday'],
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
