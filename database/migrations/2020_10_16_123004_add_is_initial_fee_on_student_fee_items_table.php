<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsInitialFeeOnStudentFeeItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_fee_items', function (Blueprint $table) {
            $table->tinyInteger('is_initial_fee')->default(0)->nullable()->after('school_fee_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_fee_items', function (Blueprint $table) {
            $table->dropColumn('is_initial_fee');
        });
    }
}
