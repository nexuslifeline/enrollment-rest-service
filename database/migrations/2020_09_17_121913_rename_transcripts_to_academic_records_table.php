<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTranscriptsToAcademicRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('transcripts', 'academic_records');
        Schema::rename('transcript_statuses', 'academic_record_statuses');
        Schema::rename('transcript_subjects', 'academic_record_subjects');
    }

}
