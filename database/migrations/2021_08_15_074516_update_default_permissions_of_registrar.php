<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDefaultPermissionsOfRegistrar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('user_group_permissions')->where(['user_group_id' => 17])
        ->whereIn('permission_id',[341,381,391])
        ->delete();

        DB::table('user_group_permissions')->insert([
            'user_group_id' => 17, 
            'permission_id' => 149
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
