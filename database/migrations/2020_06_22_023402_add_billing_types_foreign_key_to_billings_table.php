<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBillingTypesForeignKeyToBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->foreign('billing_type_id')->references('id')->on('billings');
            $table->unsignedBigInteger('billing_type_id')->after('student_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->dropForeign(['billing_type_id']);
            $table->dropColumn(['billing_type_id']);
        });
    }
}
