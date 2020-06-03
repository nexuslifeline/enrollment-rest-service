<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_no')->default('')->nullable();
            $table->string('name')->default('')->nullable();
            $table->string('first_name')->default('')->nullable();
            $table->string('middle_name')->default('')->nullable();
            $table->string('last_name')->default('')->nullable();
            $table->string('email')->default('')->nullable();
            $table->string('mobile_no')->default('')->nullable();
            $table->string('phone_no')->default('')->nullable();
            $table->string('birth_date')->default('')->nullable();
            $table->foreign('student_category_id')->references('id')->on('student_categories');
            $table->unsignedBigInteger('student_category_id')->nullable(); 
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
        Schema::dropIfExists('students');
    }
}
