<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToTranscriptRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transcript_records', function (Blueprint $table) {
            $table->tinyInteger('is_subjects_locked')
                ->comment('Determines if the subjects of transcript records will be re-created(delete all and insert again) when curriculum changed.')
                ->default(0)
                ->nullable()
                ->after('transcript_record_status_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transcript_records', function (Blueprint $table) {
            //
        });
    }
}
