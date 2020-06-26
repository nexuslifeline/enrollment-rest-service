<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovalNotesOnStudentFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_fees', function (Blueprint $table) {
            $table->string('approval_notes', 1255)->after('student_fee_status_id')->default('')->nullable();
            $table->integer('approved_by')->after('approval_notes')->nullable();
            $table->dateTime('approved_date')->after('approved_by')->nullable();
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
            $table->dropColumn('approval_notes');
            $table->dropColumn('approved_by');
            $table->dropColumn('approved_date');
        });
    }
}
