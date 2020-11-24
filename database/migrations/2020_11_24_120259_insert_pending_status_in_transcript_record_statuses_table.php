<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertPendingStatusInTranscriptRecordStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('transcript_record_statuses')->insert(
            [
                ['name' => 'Pending']
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
}
