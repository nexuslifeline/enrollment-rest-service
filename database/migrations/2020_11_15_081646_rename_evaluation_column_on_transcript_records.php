<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameEvaluationColumnOnTranscriptRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transcript_record_subjects', function (Blueprint $table) {
            $table->renameColumn('evaluation_id', 'transcript_record_id');
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
            $table->renameColumn('transcript_record_id', 'evaluation_id');
        });
    }
}
