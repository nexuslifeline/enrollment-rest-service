<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluatuionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreign('curriculum_id')->references('id')->on('curriculums');
            $table->unsignedBigInteger('curriculum_id')->nullable();
            $table->foreign('student_id')->references('id')->on('students');
            $table->unsignedBigInteger('student_id')->nullable();
            $table->foreign('student_category_id')->references('id')->on('student_categories');
            $table->unsignedBigInteger('student_category_id')->nullable();
            $table->foreign('level_id')->references('id')->on('levels');
            $table->unsignedBigInteger('level_id')->nullable();
            $table->foreign('course_id')->references('id')->on('courses');
            $table->unsignedBigInteger('course_id')->nullable();
            $table->foreign('evaluation_status_id')->references('id')->on('evaluation_statuses');
            $table->unsignedBigInteger('evaluation_status_id')->nullable();
            $table->unsignedBigInteger('last_year_attended')->nullable();
            $table->string('last_school_attended')->default('')->nullable();
            $table->unsignedBigInteger('enrolled_year')->nullable();
            $table->text('notes')->nullable();
            $table->text('approval_notes')->nullable();
            $table->text('disapproval_notes')->nullable();
            $table->integer('approved_by')->nullable();
            $table->integer('disapproved_by')->nullable();
            $table->dateTime('approved_date')->nullable();
            $table->dateTime('disapproved_date')->nullable();
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
        Schema::dropIfExists('evaluatuions');
    }
}
