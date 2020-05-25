<?php

use App\Semester;
use Illuminate\Database\Seeder;

class SemestersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => '1st Sem', 'description' => 'First Semester'],
            ['name' => '2nd Sem', 'description' => 'Second Semester'],
            ['name' => '3rd Sem', 'description' => 'Third Semester']
        ];
        foreach($data as $item){
            Semester::create($item);
        }
    }
}
