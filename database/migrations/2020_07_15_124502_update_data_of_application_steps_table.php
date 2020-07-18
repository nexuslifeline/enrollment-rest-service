<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDataOfApplicationStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('application_steps')->insert(
            [
                ['name' => 'Payments'],
                ['name' => 'Waiting'],
            ]
        );

        DB::table('application_steps')->where(['id' => 5])->update(['name' => 'Request Evaluation']);
        DB::table('application_steps')->where(['id' => 6])->update(['name' => 'Waiting Evaluation']); 
        DB::table('application_steps')->where(['id' => 7])->update(['name' => 'Academic Year - Application']);
        DB::table('application_steps')->where(['id' => 8])->update(['name' => 'Status']);
    }

}
