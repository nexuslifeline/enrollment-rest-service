<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDefaultSchoolYearStatuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::table('school_year_statuses')->insert([
            [
                'name' => 'Setup School Year',
                'description' => 'Setup School Year'
            ],
            [
                'name' => 'Setup Billing Terms',
                'description' => 'Setup Billing Terms'
            ],
            [
                'name' => 'Setup Grading Period',
                'description' => 'Setup Grading Period'
            ],
            [
                'name' => 'Setup Section & Schedule',
                'description' => 'Setup Section & Schedule'
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
