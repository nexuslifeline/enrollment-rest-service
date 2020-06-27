<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStudentsSchoolYearToPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('student_id')->references('id')->on('students');
            $table->unsignedBigInteger('student_id')->after('billing_id')->nullable();
            $table->foreign('school_year_id')->references('id')->on('school_years');
            $table->unsignedBigInteger('school_year_id')->after('student_id')->nullable();
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
            $table->dropForeign(['student_id', 'school_year_id']);
            $table->dropColumn(['student_id', 'school_year_id']);
        });
    }
}
