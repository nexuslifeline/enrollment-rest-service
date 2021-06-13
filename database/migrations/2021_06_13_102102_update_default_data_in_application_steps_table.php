<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDefaultDataInApplicationStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('application_steps')->where(['id' => 6])->update(['name' => 'Evaluation in Review']);
        DB::table('application_steps')->where(['id' => 7])->update(['name' => 'Academic Record Application']);
        DB::table('application_steps')->where(['id' => 8])->update(['name' => 'Academic Record in Review']);
        DB::table('application_steps')->where(['id' => 10])->update(['name' => 'Payment in Review']);
    }
}
