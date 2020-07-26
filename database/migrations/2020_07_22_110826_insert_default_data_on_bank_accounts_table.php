<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertDefaultDataOnBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('bank_accounts')->insert(
            [
                ['bank' => 'Development Bank of the Philippines', 'account_name' => 'Saint Theresa College of Tandag, Inc.', 'account_number' => '00-5-00264-855-4'],
            ]
        );
    }

}
