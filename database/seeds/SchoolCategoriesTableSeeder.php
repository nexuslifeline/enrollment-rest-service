<?php

use App\SchoolCategory;
use Illuminate\Database\Seeder;

class SchoolCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Pre-School', 'description' => ''],
            ['name' => 'Primary School', 'description' => ''],
            ['name' => 'Junior High School', 'description' => ''],
            ['name' => 'Senior High School', 'description' => ''],
            ['name' => 'College', 'description' => ''],
            ['name' => 'Graduate School', 'description' => '']
        ];
        foreach($data as $item){
            SchoolCategory::create($item);
        }
    }
}
