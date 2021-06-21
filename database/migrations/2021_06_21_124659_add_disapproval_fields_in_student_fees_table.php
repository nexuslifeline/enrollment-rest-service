<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDisapprovalFieldsInStudentFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_fees', function (Blueprint $table) {
            $table->string('disapproval_notes', 1255)->after('approved_date')->default('')->nullable();
            $table->integer('disapproved_by')->after('disapproval_notes')->nullable();
            $table->dateTime('disapproved_date')->after('disapproved_by')->nullable();
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
            //
        });
    }
}
