<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddDefaultDataInBillingStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('billing_statuses')->insert([
            'id' => 3,
            'name' => 'Partially Paid',
            'description' => 'Partially Paid'
        ]);
    }
}
