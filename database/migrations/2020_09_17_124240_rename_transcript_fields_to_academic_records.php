<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTranscriptFieldsToAcademicRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('academic_records', function (Blueprint $table) {
            $table->renameColumn('transcript_status_id', 'academic_record_status_id');
        });

        Schema::table('academic_record_subjects', function (Blueprint $table) {
            $table->renameColumn('transcript_id', 'academic_record_id');
        });

        Schema::table('student_fees', function (Blueprint $table) {
            $table->renameColumn('transcript_id', 'academic_record_id');
        });

    }
}
