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
        Schema::create('admission_files', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('')->nullable();
            $table->string('notes')->default('')->nullable();
            $table->string('path')->default('')->nullable();
            $table->foreign('admission_id')->references('id')->on('admissions');
            $table->unsignedBigInteger('admission_id')->nullable();
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
        Schema::dropIfExists('admission_files');
    }
}
