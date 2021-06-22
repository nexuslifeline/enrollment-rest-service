<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveIsManualFieldInApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('is_manual');
            $table->dropColumn('is_admission');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->tinyInteger('is_manual')->default(0)->nullable()->after('academic_record_id');
            $table->tinyInteger('is_admission')->default(0)->nullable()->after('disapproved_date');
        });
    }
}
