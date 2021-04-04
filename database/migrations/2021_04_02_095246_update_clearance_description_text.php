<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateClearanceDescriptionText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permission_groups')->where(['id' => 33])->update(['name' => 'Clearance Signatories']);
        DB::table('permission_groups')->where(['id' => 34])->update(['name' => 'Clearance']);

        DB::table('permissions')->where(['id' => 371])->update([
            'name' => 'Generate Clearance Signatories.',
            'description' => 'Enable feature for generating Student Clearance Signatories.'
        ]);

        DB::table('permissions')->where(['id' => 372])->update([
            'name' => 'Edit Clearance Signatories.',
            'description' => 'Enable feature for updating Student Clearance Signatories.'
        ]);

        DB::table('permissions')->where(['id' => 373])->update([
            'name' => 'Delete Clearance Signatories.',
            'description' => 'Enable feature for deleting Student Clearance Signatories.'
        ]);


        DB::table('permissions')->where(['id' => 381])->update([
            'name' => 'Update Student Clearance Status.',
            'description' => 'Enable feature for updating student clearance status.'
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
