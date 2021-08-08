<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldInStudentRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_requirements', function (Blueprint $table) {
            $table->tinyInteger('is_submitted')->default(0)->nullable()->after('school_category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_requirements', function (Blueprint $table) {
            $table->dropColumn('is_submitted');
        });
    }
}
