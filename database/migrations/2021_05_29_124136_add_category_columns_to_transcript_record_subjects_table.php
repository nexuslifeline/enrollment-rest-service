<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryColumnsToTranscriptRecordSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transcript_record_subjects', function (Blueprint $table) {
            $table->unsignedBigInteger('school_category_id')->nullable()->after('transcript_record_id');
            $table->foreign('school_category_id')->references('id')->on('school_categories');
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
            //
        });
    }
}
