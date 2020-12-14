<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddNewPermissionsPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permissions')->insert([
            ['id' => 281, 'name' => 'Add Manual Enrollment', 'description' => 'Enable feature for adding new Manual Enrollment.', 'permission_group_id' => 22],
        ]);

        DB::table('permissions')->insert([
            ['id' => 291, 'name' => 'Add Payment', 'description' => 'Enable feature for adding new Payment.', 'permission_group_id' => 23],
            ['id' => 292, 'name' => 'Cancel Payment', 'description' => 'Enable feature for cancelling Payment.', 'permission_group_id' => 23],
        ]);

        DB::table('permissions')->insert([
            ['id' => 301, 'name' => 'Generate SOA', 'description' => 'Enable feature for generating new Statement of Account (SOA).', 'permission_group_id' => 24],
            ['id' => 302, 'name' => 'Edit SOA', 'description' => 'Enable feature for editing Statement of Account (SOA).', 'permission_group_id' => 24],
            ['id' => 303, 'name' => 'Delete SOA', 'description' => 'Enable feature for deleting Statement of Account (SOA).', 'permission_group_id' => 24],
            ['id' => 304, 'name' => 'Preview SOA', 'description' => 'Enable feature for previewing Statement of Account (SOA).', 'permission_group_id' => 24],
        ]);

        DB::table('permissions')->insert([
            ['id' => 311, 'name' => 'Generate Other Billing', 'description' => 'Enable feature for generating new Other Billing.', 'permission_group_id' => 25],
            ['id' => 312, 'name' => 'Edit Other Billing', 'description' => 'Enable feature for editing Other Billing.', 'permission_group_id' => 25],
            ['id' => 313, 'name' => 'Delete Other Billing', 'description' => 'Enable feature for deleting Other Billing.', 'permission_group_id' => 25],
        ]);

        DB::table('permissions')->insert([
            ['id' => 321, 'name' => 'Review Transcript Record', 'description' => 'Enable feature for reviewing/editing Transcript Record.', 'permission_group_id' => 26],
            ['id' => 322, 'name' => 'Print Transcript Record', 'description' => 'Enable feature for printing Transcript Record.', 'permission_group_id' => 26],
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
