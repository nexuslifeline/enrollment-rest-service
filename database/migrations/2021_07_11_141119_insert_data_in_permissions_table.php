<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertDataInPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permissions')->insert(
            ['id' => 233, 'name' => 'Accept Transfer Credits', 'description' => 'Enable feature for accepting transfer credits.', 'permission_group_id' => 15]
        );
        DB::table('permissions')->where(['id' => 241])
        ->update(
            ['name' => 'Subject Enlistment Approval', 'description' => 'Enable feature for approving subject enlistment.']
        );
        DB::table('permissions')->where(['id' => 242])
        ->update(
            ['name' => 'Subject Enlistment Disapproval', 'description' => 'Enable feature for disapproving subject enlistment.']
        );
        DB::table('permissions')->where(['id' => 251])
        ->update(
            ['name' => 'Assessment Approval', 'description' => 'Enable feature for approving assessment.']
        );
        DB::table('permissions')->where(['id' => 252])
        ->update(
            ['name' => 'Assessment Disapproval', 'description' => 'Enable feature for disapproving assessment.']
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
