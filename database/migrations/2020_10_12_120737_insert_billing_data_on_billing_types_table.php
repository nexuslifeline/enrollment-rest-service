<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertBillingDataOnBillingTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('billing_types')->insert([
            ['id' => 3, 'name' => 'Billing', 'description' => '']
        ]);
        DB::table('billing_types')->where('id', 2)
        ->update([
            'name' => 'SOA'
        ]);
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
