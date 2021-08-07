<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InsertNewDataInPermissionGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permission_groups')->insert(
            ['id' => 35, 'name' => 'Drop Student', 'description' => '']
        );

        DB::table('permissions')->insert(
            ['id' => 391, 'name' => 'Manage Dropped Students', 'description' => 'Enable feature for managing Dropped Students', 'permission_group_id' => '35']
        );
    }
}
