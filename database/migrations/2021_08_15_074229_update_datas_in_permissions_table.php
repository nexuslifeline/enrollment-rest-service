<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDatasInPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permissions')->where(['id' => 341])
        ->update(
            ['name' => 'Manage Grade Sheet', 'description' => 'Enable feature for managing Grade Sheet.']
        );
    }
}
