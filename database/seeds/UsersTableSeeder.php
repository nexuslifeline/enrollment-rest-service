<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Paul Christian Rueda',
            'email' => 'chrisrueda14@yahoo.com',
            'password' => bcrypt('password'),
        ]);
    }
}
