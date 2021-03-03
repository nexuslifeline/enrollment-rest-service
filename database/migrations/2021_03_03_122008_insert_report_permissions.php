<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertReportPermissions extends Migration
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
                'id' => 29,
                'name' => 'Collection Report'
            ],
            [
                'id' => 30,
                'name' => 'Student Ledger Report'
            ],
            [
                'id' => 31,
                'name' => 'Class Masterlist Report'
            ],
        ]);

        DB::table('permissions')->insert([
            [
                'id' => 351,
                'name' => 'Viewing/Printing Collection Report',
                'description' => 'Enable feature for viewing/printing collection report.',
                'permission_group_id' => 29
            ],
            [
                'id' => 352,
                'name' => 'Viewing/Printing Student Ledger Report',
                'description' => 'Enable feature for viewing/printing student ledger report.',
                'permission_group_id' => 30
            ],
            [
                'id' => 353,
                'name' => 'Viewing/Printing Class Masterlist Report',
                'description' => 'Enable feature for viewing/printing class masterlist report.',
                'permission_group_id' => 31
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
