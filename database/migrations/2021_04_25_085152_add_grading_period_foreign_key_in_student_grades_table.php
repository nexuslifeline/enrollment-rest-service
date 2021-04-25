<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGradingPeriodForeignKeyInStudentGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_grades', function (Blueprint $table) {
            $table->unsignedBigInteger('grading_period_id')->nullable()->after('subject_id');
            $table->foreign('grading_period_id')->references('id')->on('grading_periods');
            $table->dropForeign(['term_id']);
            $table->dropColumn('term_id');
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
            $table->unsignedBigInteger('term_id')->nullable()->after('subject_id');
            $table->foreign('term_id')->references('id')->on('terms');
            $table->dropForeign(['grading_period_id']);
            $table->dropColumn('grading_period_id');
        });
    }
}
