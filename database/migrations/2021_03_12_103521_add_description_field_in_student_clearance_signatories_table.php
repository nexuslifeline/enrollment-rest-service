<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionFieldInStudentClearanceSignatoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_clearance_signatories', function (Blueprint $table) {
            $table->string('description')->default('')->nullable()->after('personnel_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_clearance_signatories', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
}
