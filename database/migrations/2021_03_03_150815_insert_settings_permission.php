<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertSettingsPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permission_groups')->insert([
            [
                'id' => 32,
                'name' => 'Settings'
            ],
        ]);

        DB::table('permissions')->insert([
            [
                'id' => 361,
                'name' => 'General Settings',
                'description' => 'Enable feature for editing General Settings.',
                'permission_group_id' => 32
            ],
            [
                'id' => 362,
                'name' => 'Organization Settings',
                'description' => 'Enable feature for editing Organization Settings.',
                'permission_group_id' => 32
            ],
            [
                'id' => 363,
                'name' => 'Terms Settings',
                'description' => 'Enable feature for editing Terms Settings.',
                'permission_group_id' => 32
            ],
            [
                'id' => 364,
                'name' => 'Requirements Settings',
                'description' => 'Enable feature for editing Requirements Settings.',
                'permission_group_id' => 32
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
