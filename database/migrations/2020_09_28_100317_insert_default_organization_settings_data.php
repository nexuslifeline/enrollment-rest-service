<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertDefaultOrganizationSettingsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('organization_settings')->insert([
            [
                'id' => 1,
                'name' => 'Nexus Lifeline',
                'address' => 'Default Address',
                'mobile_no' => 'Default Mobile',
                'telephone_no' => 'Default Telephone',
                'email_address' => 'nexuslifeline@gmail.com',
            ],
        ]);
    }

}
