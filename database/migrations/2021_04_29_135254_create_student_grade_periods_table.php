<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentGradePeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_grade_periods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_grade_id')->nullable();
            $table->foreign('student_grade_id')->references('id')->on('student_grades');
            $table->unsignedBigInteger('grading_period_id')->nullable();
            $table->foreign('grading_period_id')->references('id')->on('grading_periods');
            $table->decimal('grade', 13, 2)->default(0)->nullable();
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
        Schema::dropIfExists('student_grade_periods');
    }
}
