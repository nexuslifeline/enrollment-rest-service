<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluation_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreign('evaluation_id')->references('id')->on('evaluations');
            $table->unsignedBigInteger('evaluation_id')->nullable();
            $table->foreign('level_id')->references('id')->on('levels');
            $table->unsignedBigInteger('level_id')->nullable();
            $table->foreign('semester_id')->references('id')->on('semesters');
            $table->unsignedBigInteger('semester_id')->nullable();
            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->tinyInteger('is_allowed')->default(0)->nullable();
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
        Schema::dropIfExists('evaluation_subjects');
    }
}
