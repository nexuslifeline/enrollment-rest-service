<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTranscriptForeignKeyOnAcademicRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('academic_records', function (Blueprint $table) {
            $table->unsignedBigInteger('transcript_record_id')->nullable()->after('id');
            $table->foreign('transcript_record_id')->references('id')->on('transcript_records');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('academic_records', function (Blueprint $table) {
            $table->dropForeign(['transcript_record_id']);
            $table->dropColumn(['transcript_record_id']);
        });
    }
}
