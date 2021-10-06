<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsInPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('overpay', 13, 2)->default(0)->nullable()->after('amount');
            $table->decimal('forwarded_payment', 13, 2)->default(0)->nullable()->after('overpay');
            $table->tinyInteger('is_overpay_forwarded')->default(0)->nullable()->after('forwarded_payment');
            $table->text('system_notes')->nullable()->after('disapproval_notes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('overpay');
            $table->dropColumn('forwarded_payment');
            $table->dropColumn('is_overpay_forwarded');
            $table->dropColumn('system_notes');
        });
    }
}
