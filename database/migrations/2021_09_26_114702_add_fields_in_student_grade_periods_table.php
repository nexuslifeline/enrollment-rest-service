<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInStudentGradePeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_grade_periods', function (Blueprint $table) {
            $table->unsignedBigInteger('academic_record_id')->nullable()->after('grading_period_id');
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
        Schema::table('student_grade_periods', function (Blueprint $table) {
            $table->dropForeign(['academic_record_id']);
            $table->dropColumn('academic_record_id');
        });
    }
}
