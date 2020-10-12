<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSchoolFeesForeignKeyToBillingItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('billing_items', function (Blueprint $table) {
            $table->foreign('school_fee_id')->references('id')->on('school_fees');
            $table->unsignedBigInteger('school_fee_id')->after('item')->nullable();
            $table->foreign('term_id')->references('id')->on('terms');
            $table->unsignedBigInteger('term_id')->after('school_fee_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('billing_items', function (Blueprint $table) {
            $table->dropForeign(['school_fee_id', 'term_id']);
            $table->dropColumn('school_fee_id');
            $table->dropColumn('term_id');
        });
    }
}
