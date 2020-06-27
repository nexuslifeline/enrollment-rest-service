<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDataOnPaymentModesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('payment_modes')->where(['id' => 1])->update(['name' => 'Bank Deposit/Transfer', 'description' => 'Bank Deposit/Transfer']);
        DB::table('payment_modes')->where(['id' => 2])->update(['is_active' => false]);
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
