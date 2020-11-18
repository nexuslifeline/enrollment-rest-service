<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertDefaultDataOnTranscriptStatusesTable extends Migration
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
                ['name' => 'Draft'],
                ['name' => 'Finalized'],
            ]
        );
    }

}
