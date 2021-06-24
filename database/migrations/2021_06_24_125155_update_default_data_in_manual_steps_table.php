<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDefaultDataInManualStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('manual_steps')->where(['id' => 1])->update(['name' => 'Student Registration', 'description' => 'Student Registration']);
        DB::table('manual_steps')->where(['id' => 2])->update(['name' => 'Subject Enlistment', 'description' => 'Subject Enlistment']);
        DB::table('manual_steps')->where(['id' => 3])->update(['name' => 'Assessment', 'description' => 'Assessment']);
        DB::table('manual_steps')->where(['id' => 4])->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
