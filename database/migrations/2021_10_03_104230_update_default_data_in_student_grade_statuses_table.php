<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDefaultDataInStudentGradeStatusesTable extends Migration
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
        ->update(['name' => 'Draft', 'description' => 'Visible only to creator. No one can see the grades.']);
        DB::table('student_grade_statuses')
        ->where(['id' => 2])
        ->update(['name' => 'Published', 'description' => 'Visible to anyone including the students and the school staff.']);
        DB::table('student_grade_statuses')
        ->where(['id' => 3])
        ->update(['name' => 'Submitted for Review', 'description' => 'Locked for editing and subject for approval of registrar.']);
        DB::table('student_grade_statuses')
        ->where(['id' => 4])
        ->update(['name' => 'Request Edit', 'description' => 'Locked for editing and subject for edit request approval.']);

        DB::table('student_grade_statuses')->insertOrIgnore([
            [
                'id' => 5,
                'name' => 'Editing Approved',
                'description' => 'Publisher would able to edit the grades again.'
            ]
        ]);

        DB::table('student_grade_statuses')->insertOrIgnore([
            [
                'id' => 6,
                'name' => 'Finalized',
                'description' => 'Permanently locked. Everyone will not be able to change the grade.'
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
