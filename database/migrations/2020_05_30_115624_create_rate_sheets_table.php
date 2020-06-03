<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRateSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rate_sheets', function (Blueprint $table) {
            $table->id();
            $table->foreign('level_id')->references('id')->on('levels');
            $table->unsignedBigInteger('level_id')->nullable();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('semester_id')->nullable();
            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('semester_id')->references('id')->on('semesters');
            $table->decimal('enrollment_fee', 13, 2)->default(0)->nullable();
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
        Schema::dropIfExists('rate_sheets');
    }
}
