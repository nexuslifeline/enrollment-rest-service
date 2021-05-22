<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropFieldsInEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropForeign(['school_year_id']);
            $table->dropColumn('school_year_id');

            $table->dropForeign(['student_category_id']);
            $table->dropColumn('student_category_id');

            $table->dropForeign(['level_id']);
            $table->dropColumn('level_id');

            $table->dropForeign(['semester_id']);
            $table->dropColumn('semester_id');

            $table->dropForeign(['course_id']);
            $table->dropColumn('course_id');

            $table->dropForeign(['transcript_record_id']);
            $table->dropColumn('transcript_record_id');

            $table->dropForeign(['curriculum_id']);
            $table->dropColumn('curriculum_id');

            $table->dropForeign(['student_curriculum_id']);
            $table->dropColumn('student_curriculum_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evaluations', function (Blueprint $table) {
            //
        });
    }
}
