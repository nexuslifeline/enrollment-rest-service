<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertDefaultGeneralSettingsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('general_settings')->insert([
            [
                'id' => 1,
                'miscellaneous_fee_category_id' => 1
            ],
        ]);
    }
}
