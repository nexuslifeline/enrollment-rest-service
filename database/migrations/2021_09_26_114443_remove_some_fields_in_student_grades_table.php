<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveSomeFieldsInStudentGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_grades', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropColumn('student_id');
            $table->dropForeign(['academic_record_id']);
            $table->dropColumn('academic_record_id');
            $table->date('finalized_date')->nullable()->after('submitted_date');
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
            $table->unsignedBigInteger('student_id')->nullable()->after('id');
            $table->foreign('student_id')->references('id')->on('students');
            $table->unsignedBigInteger('academic_record_id')->nullable()->after('student_id');
            $table->foreign('academic_record_id')->references('id')->on('academic_records');
        });
    }
}
