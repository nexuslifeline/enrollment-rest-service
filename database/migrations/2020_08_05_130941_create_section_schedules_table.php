<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreign('section_id')->references('id')->on('sections');
            $table->unsignedBigInteger('section_id')->nullable();
            $table->foreign('day_id')->references('id')->on('days');
            $table->unsignedBigInteger('day_id')->nullable();
            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->foreign('personnel_id')->references('id')->on('personnels');
            $table->unsignedBigInteger('personnel_id')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->tinyInteger('is_lab')->default(0)->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('section_schedules');
    }
}
