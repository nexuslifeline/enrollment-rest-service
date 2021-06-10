<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSizeColumnInPaymentReceiptFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_receipt_files', function (Blueprint $table) {
            $table->integer('size')->default(0)->nullable()->after('hash_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_receipt_files', function (Blueprint $table) {
            $table->dropColumn('size');
        });
    }
}
