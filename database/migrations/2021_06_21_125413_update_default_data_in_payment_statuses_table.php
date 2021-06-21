<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDefaultDataInPaymentStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('payment_statuses')->where(['id' => 1])->update(['name' => 'Draft', 'description' => 'Draft']);
        DB::table('payment_statuses')->where(['id' => 4])->update(['name' => 'Pending', 'description' => 'Pending']);
    }
}
