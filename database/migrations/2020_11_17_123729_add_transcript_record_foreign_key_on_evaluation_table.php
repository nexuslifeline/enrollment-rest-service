<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTranscriptRecordForeignKeyOnEvaluationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->foreign('transcript_record_id')->references('id')->on('transcript_records');
            $table->unsignedBigInteger('transcript_record_id')->after('student_category_id')->nullable();
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
            $table->dropForeign(['transcript_record_id']);
            $table->dropColumn('transcript_record_id');
        });
    }
}
