<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInAcademicRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('academic_records', function (Blueprint $table) {
            $table->unsignedBigInteger('evaluation_id')->nullable()->after('student_id');
            $table->foreign('evaluation_id')->references('id')->on('evaluations');

            $table->unsignedBigInteger('student_curriculum_id')->nullable()->after('curriculum_id');
            $table->foreign('student_curriculum_id')->references('id')->on('curriculums');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('academic_records', function (Blueprint $table) {
            //
        });
    }
}
