<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class InsertDefaultManualStepsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('manual_steps')->insert([
            [
                'id' => 1,
                'name' => 'Student Registration',
                'description' => 'Student Registration'
            ],
            [
                'id' => 2,
                'name' => 'Evaluation',
                'description' => 'Evaluation'
            ],
            [
                'id' => 3,
                'name' => 'Subject Enlistment',
                'description' => 'Subject Enlistment'
            ],
            [
                'id' => 4,
                'name' => 'Completed',
                'description' => 'Completed'
            ],
        ]);
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
