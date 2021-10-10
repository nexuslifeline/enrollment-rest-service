<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataRejectInStudentGradeStatuses extends Migration
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
                'id' => 7,
                'name' => 'Rejected',
                'description' => 'Rejected'
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
