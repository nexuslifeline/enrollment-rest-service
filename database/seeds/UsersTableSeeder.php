<?php

use App\Personnel;
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
        $users = [
            [
                'personnel' => [
                    'name' => 'Paul Christian Rueda',
                    'first_name' => 'Paul Christian',
                    'last_name' => 'Rueda'
                ],
                'account' => [
                    'username' => 'admin@nexuslifeline.com',
                    'password' => bcrypt('password')
                ]
            ]
        ];

        foreach($users as $user) {
            $personnel = Personnel::create($user['personnel']);
            $personnel->user()->create($user['account']);
        }
    }
}
