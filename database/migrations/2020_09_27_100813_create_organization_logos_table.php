<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationLogosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_logos', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('')->nullable();
            $table->string('path')->default('')->nullable();
            $table->string('hash_name')->default('')->nullable();
            $table->foreign('organization_setting_id')->references('id')->on('organization_settings');
            $table->unsignedBigInteger('organization_setting_id')->nullable();
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
        Schema::dropIfExists('organization_logos');
    }
}
