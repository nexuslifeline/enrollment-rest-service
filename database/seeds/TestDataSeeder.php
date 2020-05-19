<?php

use App\Student;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $limit = 100;
        for ($i = 0; $i < $limit; $i++) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $date = $faker->dateTimeBetween('-30 years', 'now');
            $email = strtolower(str_replace('', ' ', $firstName . $lastName . '@nexuslifeline.com'));

            $student = Student::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'birth_date' => $date->format('Y-m-d')
            ]);

            $student->user()->create([
                'username' => $email,
                'password' => bcrypt('password')
            ]);
        }
    }
}
