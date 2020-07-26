<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertDefaultDataOnPeraPadalaAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('pera_padala_accounts')->insert(
            [
                ['provider' => 'Palawan Express', 'receiver_name' => 'Jessica G. Sacal', 'receiver_mobile_no' => '09991524201'],
            ]
        );
    }
}
