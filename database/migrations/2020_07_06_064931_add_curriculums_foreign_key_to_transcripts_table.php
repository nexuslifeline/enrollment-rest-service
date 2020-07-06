<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurriculumsForeignKeyToTranscriptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transcripts', function (Blueprint $table) {
            $table->foreign('curriculum_id')->references('id')->on('curriculums');
            $table->unsignedBigInteger('curriculum_id')->after('semester_id')->nullable();
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
            $table->dropForeign('curriculum_id');
            $table->dropColumn('curriculum_id');
        });
    }
}
