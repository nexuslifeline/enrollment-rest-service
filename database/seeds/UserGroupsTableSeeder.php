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
        ['code' => 'Super User', 'name' => 'Super User', 'description' => 'Super User'],
        ['code' => 'Registrar - Pre-School', 'name' => 'Registrar - Pre-School', 'description' => 'Registrar - Pre-School'],
        ['code' => 'Finance - Pre-School', 'name' => 'Finance - Pre-School', 'description' => 'Finance - Pre-School'],
        ['code' => 'Registrar - Primary School', 'name' => 'Registrar - Primary School', 'description' => 'Registrar - Primary School'],
        ['code' => 'Finance - Primary School', 'name' => 'Finance - Primary School', 'description' => 'Finance - Primary School'],
        ['code' => 'Registrar - Junior High School', 'name' => 'Registrar - Junior High School', 'description' => 'Registrar - Junior High School'],
        ['code' => 'Finance - Junior High School', 'name' => 'Finance - Junior High School', 'description' => 'Finance - Junior High School'],
        ['code' => 'Registrar - Senior High School', 'name' => 'Registrar - Senior High School', 'description' => 'Registrar - Senior High School'],
        ['code' => 'Finance - Senior High School', 'name' => 'Finance - Senior High School', 'description' => 'Finance - Senior High School'],
        ['code' => 'Registrar - College', 'name' => 'Registrar - College', 'description' => 'Registrar - College'],
        ['code' => 'Finance - College', 'name' => 'Finance - College', 'description' => 'Finance - College'],
        ['code' => 'Registrar - Graduate School', 'name' => 'Registrar - Graduate School', 'description' => 'Registrar - Graduate School'],
        ['code' => 'Finance - Graduate School', 'name' => 'Finance - Graduate School', 'description' => 'Finance - Graduate School'],
      ]
    );
  }
}
