<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertDataOnEvaluationStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('evaluation_statuses')->insert(
            [
                ['name' => 'Pending', 'description' => 'Pending'],
                ['name' => 'Submitted', 'description' => 'Submitted'],
                ['name' => 'Approve', 'description' => 'Approve'],
                ['name' => 'Reject', 'description' => 'Reject'],
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
