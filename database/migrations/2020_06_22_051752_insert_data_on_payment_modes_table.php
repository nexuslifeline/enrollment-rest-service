<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertDataOnPaymentModesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('payment_modes')->insert(
            [
                ['name' => 'E-Wallet (GCash, Paymaya, PayPal, Coins.ph)', 'description' => 'E-Wallet (GCash, Paymaya, PayPal, Coins.ph)']
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
