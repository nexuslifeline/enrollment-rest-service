<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSectionsForeignKeyToTranscriptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transcripts', function (Blueprint $table) {
            $table->foreign('section_id')->references('id')->on('sections');
            $table->unsignedBigInteger('section_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transcripts', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
            $table->dropColumn(['section_id']);
        });
    }
}
