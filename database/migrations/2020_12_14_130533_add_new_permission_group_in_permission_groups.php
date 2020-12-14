<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddNewPermissionGroupInPermissionGroups extends Migration
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
                ['id' => 22, 'name' => 'Manual Enrollment'],
                ['id' => 23, 'name' => 'Payment'],
                ['id' => 24, 'name' => 'Statement of Account (SOA)'],
                ['id' => 25, 'name' => 'Other Billing'],
                ['id' => 26, 'name' => 'Transcript Record']
            ]
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
