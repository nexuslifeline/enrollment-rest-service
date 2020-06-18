<?php

use Illuminate\Database\Seeder;

class BillingStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('billing_statuses')->insert(
            [
                ['name' => 'Paid', 'description' => 'Paid'],
                ['name' => 'Unpaid', 'description' => 'Unpaid'],
            ]
        );
    }
}
