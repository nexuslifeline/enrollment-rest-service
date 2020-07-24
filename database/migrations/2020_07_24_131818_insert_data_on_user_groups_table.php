<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertDataOnUserGroupsTable extends Migration
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
                ['code' => 'Instructor', 'name' => 'Instructor', 'description' => 'Instructor'],
            ]
        );
    }


}
