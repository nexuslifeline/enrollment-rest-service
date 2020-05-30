<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRateSheetItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rate_sheet_items', function (Blueprint $table) {
            $table->id();
            $table->foreign('rate_sheet_id')->references('id')->on('rate_sheets');
            $table->unsignedBigInteger('rate_sheet_id')->nullable();
            $table->foreign('school_fee_id')->references('id')->on('school_fees');
            $table->unsignedBigInteger('school_fee_id')->nullable();
            $table->decimal('amount', 13, 2)->default(0)->nullable();
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
        Schema::dropIfExists('rate_sheet_items');
    }
}
