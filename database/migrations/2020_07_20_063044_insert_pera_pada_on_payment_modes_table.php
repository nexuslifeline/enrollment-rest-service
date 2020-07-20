<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertPeraPadaOnPaymentModesTable extends Migration
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
                ['name' => 'Pera Padala', 'description' => 'Pera Padala'],
            ]
        );
    }

    
}
