<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnsInTranscriptRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transcript_records', function (Blueprint $table) {
            $table->dropForeign('transcript_records_level_id_foreign');
            $table->dropColumn('level_id');

            $table->dropForeign('transcript_records_course_id_foreign');
            $table->dropColumn('course_id');
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
