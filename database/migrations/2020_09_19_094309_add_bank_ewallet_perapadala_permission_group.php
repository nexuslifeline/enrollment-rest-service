<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankEwalletPerapadalaPermissionGroup extends Migration
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
                ['name' => 'Bank Account Management'],
                ['name' => 'E-Wallet Account Management'],
                ['name' => 'Pera Padala Account Management'],
            ]
        );
    }

}
