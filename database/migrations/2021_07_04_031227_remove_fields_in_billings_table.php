<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveFieldsInBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->dropForeign(['school_year_id']);
            $table->dropColumn('school_year_id');
            $table->dropForeign(['semester_id']);
            $table->dropColumn('semester_id');
            $table->unsignedBigInteger('academic_record_id')->nullable()->after('billing_status_id');
            $table->foreign('academic_record_id')->on('academic_records')->references('id');
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
            $table->dropForeign(['academic_record_id']);
            $table->dropColumn('academic_record_id');
            $table->unsignedBigInteger('school_year_id')->nullable()->after('billing_status_id');
            $table->foreign('school_year_id')->on('school_years')->references('id');
            $table->unsignedBigInteger('semester_id')->nullable()->after('school_year_id');
            $table->foreign('semester_id')->on('semesters')->references('id');
        });
    }
}
