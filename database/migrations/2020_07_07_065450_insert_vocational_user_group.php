<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertVocationalUserGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('user_groups')->insert(
            [
                ['code' => 'Registrar - Vocational', 'name' => 'Registrar - Vocational', 'description' => 'Registrar - Vocational'],
                ['code' => 'Finance - Vocational', 'name' => 'Finance - Vocational', 'description' => 'Finance - Vocational'],
            ]
        );
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
