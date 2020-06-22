<?php

use Illuminate\Database\Seeder;

class PaymentModesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_modes')->insert(
            [
                ['name' => 'Bank Deposit/Transfer', 'description' => 'Bank Deposit'],
                ['name' => 'E-Wallet', 'description' => '7-Eleven'],
                ['name' => 'Others', 'description' => 'Others'],
            ]
        );
    }
}
