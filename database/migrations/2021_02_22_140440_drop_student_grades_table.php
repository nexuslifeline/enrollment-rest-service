<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropStudentGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_grade_details', function (Blueprint $table) {
            $table->dropForeign(['student_grade_id']);
            $table->dropColumn('student_grade_id');
        });
        Schema::dropIfExists('student_grades');
        Schema::rename('student_grade_details', 'student_grades');
        Schema::table('student_grades', function (Blueprint $table) {
            $table->unsignedBigInteger('academic_record_id')->nullable()->after('id');
            $table->foreign('academic_record_id')->references('id')->on('academic_records');
            $table->unsignedBigInteger('subject_id')->nullable()->after('academic_record_id');
            $table->foreign('subject_id')->references('id')->on('subjects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
