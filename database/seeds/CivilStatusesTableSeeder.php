<?php

use App\CivilStatus;
use Illuminate\Database\Seeder;

class CivilStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Single', 'description' => 'Single'],
            ['name' => 'Married', 'description' => 'Married'],
            ['name' => 'Divorced', 'description' => 'Divorced'],
            ['name' => 'Widowed', 'description' => 'Widowed']
        ];
        foreach($data as $item){
            CivilStatus::create($item);
        }
    }
}
