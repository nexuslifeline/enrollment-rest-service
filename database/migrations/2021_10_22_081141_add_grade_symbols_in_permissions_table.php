<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGradeSymbolsInPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permission_groups')->insert([
            ['id' => 36, 'name' => 'Grade Symbol Management'],
        ]);

        DB::table('permissions')->insert([
            ['id' => 401, 'name' => 'Add Grade Symbol', 'description' => 'Enable feature for adding new grade symbol.', 'permission_group_id' => 36],
            ['id' => 402, 'name' => 'Edit Grade Symbol', 'description' => 'Enable feature for editing grade symbol.', 'permission_group_id' => 36],
            ['id' => 403, 'name' => 'Delete Grade Symbol', 'description' => 'Enable feature for deleting grade symbol.', 'permission_group_id' => 36],
        ]);
    }
}
