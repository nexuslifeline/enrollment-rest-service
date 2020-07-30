<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFromAndToFieldsToStudentPreviousEducationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_previous_educations', function (Blueprint $table) {
            $table->renameColumn('last_school_year_attended', 'last_school_year_from');
            $table->string('last_school_year_to')->after('last_school_year_attended')->default('')->nullable();

            $table->renameColumn('elementary_course_year', 'elementary_course_year_from');
            $table->string('elementary_course_year_to')->after('elementary_course_year')->default('')->nullable();

            $table->renameColumn('high_school_course_year', 'high_school_course_year_from');
            $table->string('high_school_course_year_to')->after('high_school_course_year')->default('')->nullable();

            $table->renameColumn('senior_school_course_year', 'senior_school_course_year_from');
            $table->string('senior_school_course_year_to')->after('senior_school_course_year')->default('')->nullable();

            $table->renameColumn('college_degree_year', 'college_degree_year_from');
            $table->string('college_degree_year_to')->after('college_degree_year')->default('')->nullable();
        });
    }
}
