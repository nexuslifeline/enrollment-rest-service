<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDataInStudentGradeStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('student_grade_statuses')
        ->where(['id' => 1])
        ->update(['name' => 'Draft','description' => 'Draft']);
        DB::table('student_grade_statuses')
        ->where(['id' => 2])
        ->update(['name' => 'Published','description' => 'Published']);
        DB::table('student_grade_statuses')
        ->where(['id' => 3])
        ->update(['name' => 'Submitted for Review','description' => 'Submitted for Review']);

        DB::table('student_grade_statuses')->insertOrIgnore([
            [
                'id' => 4,
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
