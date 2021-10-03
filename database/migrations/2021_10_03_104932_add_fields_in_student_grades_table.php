<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInStudentGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_grades', function (Blueprint $table) {
            $table->date('published_date')->nullable()->after('student_grade_status_id');
            $table->date('edit_requested_date')->nullable()->after('student_grade_status_id');
            $table->date('request_approved_date')->nullable()->after('student_grade_status_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_grades', function (Blueprint $table) {
            $table->dropColumn('published_date');
            $table->dropColumn('edit_requested_date');
            $table->dropColumn('request_approved_date');
        });
    }
}
