<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurriculumPrerequisitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curriculum_prerequisites', function (Blueprint $table) {
            $table->id();
            $table->foreign('curriculum_id')->references('id')->on('curriculums');
            $table->unsignedBigInteger('curriculum_id')->nullable();
            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->foreign('prerequisite_subject_id')->references('id')->on('subjects');
            $table->unsignedBigInteger('prerequisite_subject_id')->nullable();
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
        Schema::dropIfExists('curriculum_prerequisites');
    }
}
