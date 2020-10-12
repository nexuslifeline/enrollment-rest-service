<?php

use Illuminate\Database\Seeder;

class BillingTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('billing_types')->insert(
            [
                ['name' => 'Initial Fee', 'description' => ''],
                ['name' => 'SOA', 'description' => ''],
                ['name' => 'BILLING', 'description' => ''],
            ]
        );
    }
}
