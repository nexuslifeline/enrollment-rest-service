<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewDataInPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permissions')->insert([
            ['id' => 147, 'name' => 'Manage Onboarding Settings', 'description' => 'Enable feature for managing Student Onboarding Settings.', 'permission_group_id' => '6'],
            ['id' => 148, 'name' => 'Edit Evaluation ', 'description' => 'Enable feature for editing Student Evaluation.', 'permission_group_id' => '6'],
            ['id' => 149, 'name' => 'Manage Dropped Subjects', 'description' => 'Enable feature for managing Dropped Subjects.', 'permission_group_id' => '6']
        ]);

        DB::table('permissions')->where(['id' => 143])
        ->update(
            ['name' => 'Manage Student Account Settings', 'description' => 'Enable feature for managing Student Account Settings.']
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            //
        });
    }
}
