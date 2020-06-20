<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentPreviousEducationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_previous_educations', function (Blueprint $table) {
            $table->id();
            $table->string('last_school_attended')->default('')->nullable();
            $table->string('last_school_address')->default('')->nullable();
            $table->string('year')->default('')->nullable();
            $table->string('elementary_course')->default('')->nullable();
            $table->string('elementary_course_year')->default('')->nullable();
            $table->string('elementary_course_honors')->default('')->nullable();
            $table->string('high_school_course')->default('')->nullable();
            $table->string('high_school_course_year')->default('')->nullable();
            $table->string('high_school_course_honors')->default('')->nullable();
            $table->string('senior_school_course')->default('')->nullable();
            $table->string('senior_school_course_year')->default('')->nullable();
            $table->string('senior_school_course_honors')->default('')->nullable();
            $table->string('college_degree')->default('')->nullable();
            $table->string('college_degree_year')->default('')->nullable();
            $table->string('college_degree_honors')->default('')->nullable();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->foreign('student_id')->references('id')->on('students');
            $table->integer('deleted_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_previous_educations');
    }
}
