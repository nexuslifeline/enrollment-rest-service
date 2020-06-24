<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApproveFieldsToPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('approval_notes')->after('payment_status_id')->default('')->nullable();
            $table->string('disapproval_notes')->after('approval_notes')->default('')->nullable();
            $table->integer('approved_by')->after('disapproval_notes')->nullable();
            $table->integer('disapproved_by')->after('approved_by')->nullable();
            $table->dateTime('approved_date')->after('disapproved_by')->nullable();
            $table->dateTime('disapproved_date')->after('approved_date')->nullable();
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
            $table->dropColumn('approval_notes');
            $table->dropColumn('disapproval_notes');
            $table->dropColumn('approved_by');
            $table->dropColumn('disapproved_by');
            $table->dropColumn('approved_date');
            $table->dropColumn('disapproved_date');
        });
    }
}