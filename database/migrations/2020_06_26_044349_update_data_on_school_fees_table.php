<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDataOnSchoolFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // change caption of data id 1 and 2
        DB::table('school_fees')->where(['id' => 1])->update(['name' => 'Tuition Fee per Unit']);
        DB::table('school_fees')->where(['id' => 2])->update(['name' => 'Tuition Fee']);
    }

}
