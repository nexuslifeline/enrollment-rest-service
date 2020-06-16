<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_photos', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('')->nullable();
            $table->string('path')->default('')->nullable();
            $table->foreign('student_id')->references('id')->on('students');
            $table->unsignedBigInteger('student_id')->nullable();
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
        Schema::dropIfExists('student_photos');
    }
}
