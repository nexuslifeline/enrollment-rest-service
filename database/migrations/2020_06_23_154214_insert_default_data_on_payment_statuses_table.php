<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertDefaultDataOnPaymentStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('payment_statuses')->insert(
            [
                ['name' => 'Pending', 'description' => 'Pending'],
                ['name' => 'Approve', 'description' => 'Approve'],
                ['name' => 'Rejected', 'description' => 'Rejected']
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
