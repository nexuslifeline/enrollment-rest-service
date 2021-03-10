<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertStudentGradePermission extends Migration
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
                'id' => 28,
                'name' => 'Student Grade Management'
            ],
        ]);

        DB::table('permissions')->insert([
            [
                'id' => 341,
                'name' => 'Edit Student Grade',
                'description' => 'Enable feature for editing Student Grade.',
                'permission_group_id' => 28
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
