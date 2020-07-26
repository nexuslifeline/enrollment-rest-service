<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonnelPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personnel_photos', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('')->nullable();
            $table->string('path')->default('')->nullable();
            $table->string('hash_name')->default('')->nullable();
            $table->foreign('personnel_id')->references('id')->on('personnels');
            $table->unsignedBigInteger('personnel_id')->nullable();
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
        Schema::dropIfExists('personnel_photos');
    }
}
