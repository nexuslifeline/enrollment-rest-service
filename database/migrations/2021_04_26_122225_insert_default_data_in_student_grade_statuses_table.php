<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertDefaultDataInStudentGradeStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('student_grade_statuses')->insert([
            [
                'name' => 'Pending',
                'description' => 'Pending'
            ],
            [
                'name' => 'Submitted',
                'description' => 'Submitted'
            ],
            [
                'name' => 'Finalized',
                'description' => 'Finalized'
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
