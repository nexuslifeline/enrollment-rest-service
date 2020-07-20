<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeraPadalaAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pera_padala_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->default('')->nullable();
            $table->string('receiver_name')->default('')->nullable();
            $table->string('receiver_mobile_no')->default('')->nullable();
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
        Schema::dropIfExists('pera_padala_accounts');
    }
}
