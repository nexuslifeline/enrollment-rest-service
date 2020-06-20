<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountriesForeignKeyToStudentAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_addresses', function (Blueprint $table) {
            $table->unsignedBigInteger('current_country_id')->nullable();
            $table->foreign('current_country_id')->references('id')->on('countries');
            $table->unsignedBigInteger('permanent_country_id')->nullable();
            $table->foreign('permanent_country_id')->references('id')->on('countries');
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
            $table->dropColumn('current_country_id');
            $table->dropColumn('permanent_country_id');
        });
    }
}
