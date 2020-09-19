<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultBankEwalletPerapadalaPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permissions')->insert(
            [
                ['name' => 'Add Bank Account', 'description' => 'Enable feature for adding new Bank Account.', 'permission_group_id' => 19],
                ['name' => 'Edit Bank Account', 'description' => 'Enable feature for editing new Bank Account.', 'permission_group_id' => 19],
                ['name' => 'Delete Bank Account', 'description' => 'Enable feature for deleting new Bank Account.', 'permission_group_id' => 19],
                ['name' => 'Add E-Wallet Account', 'description' => 'Enable feature for adding new E-Wallet Account.', 'permission_group_id' => 20],
                ['name' => 'Edit E-Wallet Account', 'description' => 'Enable feature for editing new E-Wallet Account.', 'permission_group_id' => 20],
                ['name' => 'Delete E-Wallet Account', 'description' => 'Enable feature for deleting new E-Wallet Account.', 'permission_group_id' => 20],
                ['name' => 'Add Pera Padala Account', 'description' => 'Enable feature for adding new Pera Padala Account.', 'permission_group_id' => 21],
                ['name' => 'Edit Pera Padala Account', 'description' => 'Enable feature for editing new Pera Padala Account.', 'permission_group_id' => 21],
                ['name' => 'Delete Pera Padala Account', 'description' => 'Enable feature for deleting new Pera Padala Account.', 'permission_group_id' => 21],
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
        //
    }
}
