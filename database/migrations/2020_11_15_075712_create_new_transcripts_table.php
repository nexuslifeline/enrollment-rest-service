<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewTranscriptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transcript_records', function (Blueprint $table) {
            $table->id();
            $table->foreign('curriculum_id', 'trans')->references('id')->on('curriculums');
            $table->unsignedBigInteger('curriculum_id')->nullable();
            $table->foreign('student_curriculum_id')->references('id')->on('curriculums');
            $table->unsignedBigInteger('student_curriculum_id')->nullable();
            $table->foreign('student_id')->references('id')->on('students');
            $table->unsignedBigInteger('student_id')->nullable();
            $table->foreign('school_category_id')->references('id')->on('school_categories');
            $table->unsignedBigInteger('school_category_id')->nullable();
            $table->foreign('level_id')->references('id')->on('levels');
            $table->unsignedBigInteger('level_id')->nullable();
            $table->foreign('course_id')->references('id')->on('courses');
            $table->unsignedBigInteger('course_id')->nullable();
            $table->foreign('transcript_record_status_id')->references('id')->on('transcript_record_statuses');
            $table->unsignedBigInteger('transcript_record_status_id')->nullable();
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
        Schema::dropIfExists('transcript_records');
    }
}
