<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsInApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->tinyInteger('is_completed')->nullable()->default(0)->after('disapproved_date');
            $table->date('completed_date')->nullable()->after('is_completed');
            $table->tinyInteger('is_admission')->nullable()->default(0)->after('completed_date');
            $table->dropForeign(['application_step_id']);
            $table->dropColumn('application_step_id');
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
            $table->dropColumn('is_completed');
            $table->dropColumn('completed_date');
            $table->dropColumn('is_admission');
        });
    }
}
