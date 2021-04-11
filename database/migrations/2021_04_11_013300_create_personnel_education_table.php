<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonnelEducationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personnel_education', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personnel_id')->nullable();
            $table->foreign('personnel_id')->references('id')->on('personnels');
            $table->string('school')->default('')->nullable();
            $table->string('degree')->default('')->nullable();
            $table->string('address')->default('')->nullable();
            $table->string('field')->default('')->nullable();
            $table->string('start_year', 4)->default('')->nullable();
            $table->string('end_year', 4)->default('')->nullable();
            $table->string('societies')->default('')->nullable();
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
        Schema::dropIfExists('personnel_education');
    }
}
