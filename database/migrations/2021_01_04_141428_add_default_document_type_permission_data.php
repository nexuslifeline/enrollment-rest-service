<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultDocumentTypePermissionData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permission_groups')->insert(
            [
                ['id' => 27, 'name' => 'Document Type Management'],
            ]
        );

        DB::table('permissions')->insert([
            ['id' => 331, 'name' => 'Add Document Type', 'description' => 'Enable feature for adding new Document Type..', 'permission_group_id' => 27],
            ['id' => 332, 'name' => 'Edit Document Type', 'description' => 'Enable feature for editing Document Type.', 'permission_group_id' => 27],
            ['id' => 333, 'name' => 'Delete Document Type', 'description' => 'Enable feature for deleting Document Type.', 'permission_group_id' => 27],
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
