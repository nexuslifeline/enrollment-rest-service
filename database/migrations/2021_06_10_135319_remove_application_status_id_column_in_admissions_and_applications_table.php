<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveApplicationStatusIdColumnInAdmissionsAndApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admissions', function (Blueprint $table) {
            $table->dropForeign(['application_status_id']);
            $table->dropColumn('application_status_id');
        });
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['application_status_id']);
            $table->dropColumn('application_status_id');
        });
    }
}
