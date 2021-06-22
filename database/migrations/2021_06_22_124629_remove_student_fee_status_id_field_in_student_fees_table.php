<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveStudentFeeStatusIdFieldInStudentFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_fees', function (Blueprint $table) {
            $table->dropForeign(['student_fee_status_id']);
            $table->dropColumn('student_fee_status_id');
            $table->date('submitted_date')->nullable()->after('disapproved_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_fees', function (Blueprint $table) {
            $table->unsignedBigInteger('student_fee_status_id')->nullable()->after('enrollment_fee');
            $table->foreign('student_fee_status_id')->references('id')->on('student_fee_statuses');
            $table->dropColumn('submitted_date');
        });
    }
}
