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
            $table->string('current_house_no_street')->default('')->nullable();
            $table->string('current_city_town')->default('')->nullable();
            $table->string('current_province')->default('')->nullable();
            $table->string('current_region')->default('')->nullable();
            $table->string('current_district')->default('')->nullable();
            $table->string('current_postal_code')->default('')->nullable();
            $table->string('current_complete_address')->default('')->nullable();
            $table->string('current_home_landline_mobile_no')->default('')->nullable();
            $table->string('permanent_house_no_street')->default('')->nullable();
            $table->string('permanent_city_town')->default('')->nullable();
            $table->string('permanent_province')->default('')->nullable();
            $table->string('permanent_region')->default('')->nullable();
            $table->string('permanent_district')->default('')->nullable();
            $table->string('permanent_postal_code')->default('')->nullable();
            $table->string('permanent_complete_address')->default('')->nullable();
            $table->string('permanent_home_landline_mobile_no')->default('')->nullable();
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
