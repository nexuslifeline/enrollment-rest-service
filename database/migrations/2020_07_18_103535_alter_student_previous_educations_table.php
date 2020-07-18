<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterStudentPreviousEducationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_previous_educations', function ($table) {
            $table->renameColumn('year', 'last_level');
            $table->string('last_school_year_attended')->after('last_school_address')->nullable();
        });
    }

}
