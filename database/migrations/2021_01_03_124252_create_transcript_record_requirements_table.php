<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTranscriptRecordRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transcript_record_requirements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transcript_record_id')->nullable();
            $table->foreign('transcript_record_id')->references('id')->on('transcript_records');
            $table->unsignedBigInteger('requirement_id')->nullable();
            $table->foreign('requirement_id')->references('id')->on('requirements');
            $table->integer('deleted_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transcript_record_requirements');
    }
}
