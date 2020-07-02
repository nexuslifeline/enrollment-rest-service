<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurriculumsForeignKeyToLevelSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('level_subjects', function (Blueprint $table) {
            $table->foreign('curriculum_id')->references('id')->on('curriculums');
            $table->unsignedBigInteger('curriculum_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('level_subjects', function (Blueprint $table) {
            $table->dropForeign('curriculum_id');
            $table->dropColumn('curriculum_id');
        });
    }
}
