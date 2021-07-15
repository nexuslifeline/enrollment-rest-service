<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultDataInPaymentModesTable extends Migration
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
                ['name' => 'Cash', 'description' => 'Cash'],
                ['name' => 'Check', 'description' => 'Check']
            ]
        );
    }
}
