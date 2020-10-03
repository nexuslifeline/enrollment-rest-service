<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertEditSubjectPriceDataOnPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permissions')->insert([
            ['id' => 272, 'name' => 'Edit Subject Pricing', 'description' => 'Enable feature for editing Subject price.', 'permission_group_id' => 13],
        ]);
        DB::table('permissions')->where('id', 212)
        ->update([
            'description' => 'Enable feature for editing Subject details.'
        ]);
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
