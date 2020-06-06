<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentAdmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->dateTime('applied_date')->nullable();
            $table->foreign('student_id')->references('id')->on('students');
            $table->unsignedBigInteger('student_id')->nullable();
            $table->foreign('school_year_id')->references('id')->on('school_years');
            $table->unsignedBigInteger('school_year_id')->nullable();
            $table->foreign('application_status_id')->references('id')->on('application_statuses');
            $table->unsignedBigInteger('application_status_id')->nullable();
            $table->foreign('admission_step_id')->references('id')->on('admission_steps');
            $table->unsignedBigInteger('admission_step_id')->nullable();
            $table->string('approval_notes')->default('')->nullable();
            $table->string('disapproval_notes')->default('')->nullable();
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
        Schema::dropIfExists('student_admissions');
    }
}
