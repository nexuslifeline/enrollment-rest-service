<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveFieldsInStudentGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_grades', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropColumn('course_id');
            $table->dropForeign(['level_id']);
            $table->dropColumn('level_id');
            $table->dropForeign(['semester_id']);
            $table->dropColumn('semester_id');
            $table->dropForeign(['school_year_id']);
            $table->dropColumn('school_year_id');
            $table->unsignedBigInteger('academic_record_id')->nullable()->after('student_id');
            $table->foreign('academic_record_id')->references('id')->on('academic_records');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_grades', function (Blueprint $table) {
            $table->unsignedBigInteger('school_year_id')->nullable()->after('student_id');
            $table->foreign('school_year_id')->references('id')->on('school_years');
            $table->unsignedBigInteger('course_id')->nullable()->after('school_year_id');
            $table->foreign('course_id')->references('id')->on('courses');
            $table->unsignedBigInteger('level_id')->nullable()->after('course_id');
            $table->foreign('level_id')->references('id')->on('levels');
            $table->unsignedBigInteger('semester_id')->nullable()->after('course_id');
            $table->foreign('semester_id')->references('id')->on('semesters');
            $table->dropForeign(['academic_record_id']);
            $table->dropColumn('academic_record_id');
        });
    }
}
