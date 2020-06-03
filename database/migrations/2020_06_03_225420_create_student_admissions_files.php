<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentAdmissionsFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_admissions_files', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('')->nullable();
            $table->string('notes')->default('')->nullable();
            $table->string('path')->default('')->nullable();
            $table->foreign('student_admission_id')->references('id')->on('student_admissions');
            $table->unsignedBigInteger('student_admission_id')->nullable();
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
        Schema::dropIfExists('student_admissions_files');
    }
}
