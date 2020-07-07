<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSchoolFeeCategoriesForeignKeyToSchoolFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('school_fees', function (Blueprint $table) {
            $table->foreign('school_fee_category_id')->references('id')->on('school_fee_categories');
            $table->unsignedBigInteger('school_fee_category_id')->after('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('school_fees', function (Blueprint $table) {
            $table->dropForeign('school_fee_category_id');
            $table->dropColumn('school_fee_category_id');
        });
    }
}
