<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDataInPermissionGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permission_groups')->where(['id' => 16])
        ->update(
            ['name' => 'Subject Enlistment Approval']
        );
        DB::table('permission_groups')->where(['id' => 17])
        ->update(
            ['name' => 'Assessment Approval']
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permission_groups', function (Blueprint $table) {
            //
        });
    }
}
