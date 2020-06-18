<?php

use Illuminate\Database\Seeder;

class UserGroupsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('user_groups')->insert(
      [
        ['name' => 'Super User', 'description' => 'Super User'],
        ['name' => 'Registrar - Pre-School', 'description' => 'Registrar - Pre-School'],
        ['name' => 'Finance - Pre-School', 'description' => 'Finance - Pre-School'],
        ['name' => 'Registrar - Primary School', 'description' => 'Registrar - Primary School'],
        ['name' => 'Finance - Primary School', 'description' => 'Finance - Primary School'],
        ['name' => 'Registrar - Junior High School', 'description' => 'Registrar - Junior High School'],
        ['name' => 'Finance - Junior High School', 'description' => 'Finance - Junior High School'],
        ['name' => 'Registrar - Senior High School', 'description' => 'Registrar - Senior High School'],
        ['name' => 'Finance - Senior High School', 'description' => 'Finance - Senior High School'],
        ['name' => 'Registrar - College', 'description' => 'Registrar - College'],
        ['name' => 'Finance - College', 'description' => 'Finance - College'],
        ['name' => 'Registrar - Graduate School', 'description' => 'Registrar - Graduate School'],
        ['name' => 'Finance - Graduate School', 'description' => 'Finance - Graduate School'],
      ]
    );
  }
}
