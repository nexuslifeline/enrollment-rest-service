<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveSomeFieldsInApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            // $table->dropForeign(['school_category_id']);
            // $table->dropColumn('school_category_id');
            $table->dropForeign(['school_year_id']);
            $table->dropColumn('school_year_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->unsignedBigInteger('school_year_id')->nullable()->after('applied_date');
            $table->foreign('school_year_id')->references('id')->on('school_year_id');
        });
    }
}
