<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('address')->default('')->nullable();
            $table->string('permanent_address')->default('')->nullable();
            $table->string('home_landline_mobile_no')->default('')->nullable();
            $table->string('city')->default('')->nullable();
            $table->string('postal_code')->default('')->nullable();
            $table->string('province')->default('')->nullable();
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
        Schema::dropIfExists('student_addresses');
    }
}
