<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTranscriptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transcripts', function (Blueprint $table) {
            $table->id();
            $table->foreign('student_id')->references('id')->on('students');
            $table->unsignedBigInteger('student_id')->nullable();
            $table->foreign('student_application_id')->references('id')->on('student_applications');
            $table->unsignedBigInteger('student_application_id')->nullable();
            $table->foreign('school_year_id')->references('id')->on('school_years');
            $table->unsignedBigInteger('school_year_id')->nullable();
            $table->foreign('level_id')->references('id')->on('levels');
            $table->unsignedBigInteger('level_id')->nullable();
            $table->foreign('course_id')->references('id')->on('courses');
            $table->unsignedBigInteger('course_id')->nullable();
            $table->foreign('semester_id')->references('id')->on('semesters');
            $table->unsignedBigInteger('semester_id')->nullable();
            $table->foreign('school_category_id')->references('id')->on('school_categories');
            $table->unsignedBigInteger('school_category_id')->nullable();
            $table->foreign('student_category_id')->references('id')->on('student_categories');
            $table->unsignedBigInteger('student_category_id')->nullable();
            $table->foreign('student_type_id')->references('id')->on('student_types');
            $table->unsignedBigInteger('student_type_id')->nullable();
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
        Schema::dropIfExists('transcripts');
    }
}
