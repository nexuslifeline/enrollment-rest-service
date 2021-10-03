<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldInTranscriptRecordSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transcript_record_subjects', function (Blueprint $table) {
            $table->unsignedBigInteger('student_grade_id')->nullable()->after('is_taken');
            $table->foreign('student_grade_id')->references('id')->on('student_grades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transcript_record_subjects', function (Blueprint $table) {
            $table->dropForeign(['student_grade_id']);
            $table->dropColumn('student_grade_id');
        });
    }
}
