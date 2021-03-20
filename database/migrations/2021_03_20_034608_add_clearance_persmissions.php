<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClearancePersmissions extends Migration
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
                'id' => 33,
                'name' => 'E-Clearance Management'
            ],
        ]);

        DB::table('permissions')->insert([
            [
                'id' => 371,
                'name' => 'Add E-Clearance',
                'description' => 'Enable feature for adding new E-Clearance.',
                'permission_group_id' => 33
            ],
            [
                'id' => 372,
                'name' => 'Edit E-Clearance',
                'description' => 'Enable feature for editing E-Clearance.',
                'permission_group_id' => 33
            ],
            [
                'id' => 373,
                'name' => 'Delete E-Clearance',
                'description' => 'Enable feature for deleting E-Clearance.',
                'permission_group_id' => 33
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
