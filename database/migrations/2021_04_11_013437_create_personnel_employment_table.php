<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonnelEmploymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personnel_employments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personnel_id')->nullable();
            $table->foreign('personnel_id')->references('id')->on('personnels');
            $table->string('position')->default('')->nullable();
            $table->string('company')->default('')->nullable();
            $table->string('address')->default('')->nullable();
            $table->string('start_month', 2)->default('')->nullable();
            $table->string('start_year', 4)->default('')->nullable();
            $table->string('end_month', 2)->default('')->nullable();
            $table->string('end_year', 4)->default('')->nullable();
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
        Schema::dropIfExists('personnel_employment');
    }
}
