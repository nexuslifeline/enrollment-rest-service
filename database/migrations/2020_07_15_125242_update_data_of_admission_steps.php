<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDataOfAdmissionSteps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('admission_steps')->insert(
            [
                ['name' => 'Payments'],
                ['name' => 'Waiting'],
            ]
        );

        DB::table('admission_steps')->where(['id' => 5])->update(['name' => 'Request Evaluation']);
        DB::table('admission_steps')->where(['id' => 6])->update(['name' => 'Waiting Evaluation']); 
        DB::table('admission_steps')->where(['id' => 7])->update(['name' => 'Academic Year - Application']);
        DB::table('admission_steps')->where(['id' => 8])->update(['name' => 'Requirements']);
        DB::table('admission_steps')->where(['id' => 9])->update(['name' => 'Status']);
    }
}
