<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonnelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personnels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('')->nullable();
            $table->string('first_name')->default('')->nullable();
            $table->string('middle_name')->default('')->nullable();
            $table->string('last_name')->default('')->nullable();
            $table->string('mobile_no')->default('')->nullable();
            $table->string('phone_no')->default('')->nullable();
            $table->string('birth_date')->default('')->nullable();
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
        Schema::dropIfExists('personnels');
    }
}
