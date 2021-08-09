<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDataInPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permissions')->where(['id' => 142])
            ->update(
                ['name' => 'Edit Student Information','description' => 'Enable feature for editing Student Information.']
            );

        DB::table('permissions')->where(['id' => 143])
        ->update(
            ['name' => 'Manage Student Settings', 'description' => 'Enable feature for managing Student Settings.']
        );

        DB::table('permissions')->insert(
            ['id' => 145, 'name' => 'Edit Academic Record', 'description' => 'Enable feature for editing Student Academic Record.', 'permission_group_id' => '6']
        );

        DB::table('permissions')->insert(
            ['id' => 146, 'name' => 'Edit Requirements', 'description' => 'Enable feature for editing Student Requirements.', 'permission_group_id' => '6']
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
