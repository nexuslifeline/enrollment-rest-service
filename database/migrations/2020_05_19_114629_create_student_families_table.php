<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentFamiliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_families', function (Blueprint $table) {
            $table->id();
            $table->string('mother_name')->default('')->nullable();
            $table->string('mother_occupation')->default('')->nullable();
            $table->string('mother_mobile_no')->default('')->nullable();
            $table->string('mother_phone_no')->default('')->nullable();
            $table->string('mother_email')->default('')->nullable();
            $table->string('father_name')->default('')->nullable();
            $table->string('father_occupation')->default('')->nullable();
            $table->string('father_mobile_no')->default('')->nullable();
            $table->string('father_phone_no')->default('')->nullable();
            $table->string('father_email')->default('')->nullable();
            $table->string('parent_guardian_name')->default('')->nullable();
            $table->string('parent_guardian_contact_no')->default('')->nullable();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->foreign('student_id')->references('id')->on('students');
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
        Schema::dropIfExists('student_families');
    }
}
