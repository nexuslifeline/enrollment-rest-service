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
                    'name' => 'Nexuslifeline',
                    'first_name' => 'Nexus',
                    'last_name' => 'Lifeline'
                ],
                'account' => [
                    'username' => 'admin@nexuslifeline.com',
                    'password' => bcrypt('password'),
                    'user_group_id' => 1,
                ]
            ]
        ];

        foreach($users as $user) {
            $personnel = Personnel::create($user['personnel']);
            $personnel->user()->create($user['account']);
        }
    }
}
