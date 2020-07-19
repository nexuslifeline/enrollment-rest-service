<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBarangayFieldToStudentAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_addresses', function (Blueprint $table) {
            $table->string('current_barangay')->after('current_house_no_street')->nullable();
            $table->string('permanent_barangay')->after('permanent_house_no_street')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_addresses', function (Blueprint $table) {
            $table->dropColumn('current_barangay');
            $table->dropColumn('permanent_barangay');
        });
    }
}
