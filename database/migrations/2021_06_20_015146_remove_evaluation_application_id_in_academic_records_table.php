<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveEvaluationApplicationIdInAcademicRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('academic_records', function (Blueprint $table) {
            $table->dropForeign(['evaluation_id']);
            $table->dropColumn('evaluation_id');
            $table->dropForeign('transcripts_application_id_foreign');
            $table->dropColumn('application_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('academic_records', function (Blueprint $table) {
            //
        });
    }
}
