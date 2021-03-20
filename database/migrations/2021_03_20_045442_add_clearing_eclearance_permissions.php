<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClearingEclearancePermissions extends Migration
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
                'id' => 34,
                'name' => 'E-Clearance Clearing'
            ],
        ]);

        DB::table('permissions')->insert([
            [
                'id' => 381,
                'name' => 'Clearing E-Clearance',
                'description' => 'Enable feature for clearing E-Clearance.',
                'permission_group_id' => 34
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
