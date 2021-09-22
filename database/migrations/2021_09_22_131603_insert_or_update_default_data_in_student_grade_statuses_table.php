<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InsertOrUpdateDefaultDataInStudentGradeStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('student_grade_statuses')->insertOrIgnore([
            [
                'id' => 1,
                'name' => 'Pending',
                'description' => 'Pending'
            ],
            [
                'id' => 2,
                'name' => 'Submitted',
                'description' => 'Submitted'
            ],
            [
                'id' => 3,
                'name' => 'Finalized',
                'description' => 'Finalized'
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_grade_statuses', function (Blueprint $table) {
            //
        });
    }
}
