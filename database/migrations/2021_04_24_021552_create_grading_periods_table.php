<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradingPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grading_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('')->nullable();
            $table->string('description')->default('')->nullable();
            $table->foreign('school_year_id')->references('id')->on('school_years');
            $table->unsignedBigInteger('school_year_id')->nullable();
            $table->foreign('school_category_id')->references('id')->on('school_categories');
            $table->unsignedBigInteger('school_category_id')->nullable();
            $table->foreign('semester_id')->references('id')->on('semesters');
            $table->unsignedBigInteger('semester_id')->nullable();
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
        Schema::dropIfExists('grading_periods');
    }
}
