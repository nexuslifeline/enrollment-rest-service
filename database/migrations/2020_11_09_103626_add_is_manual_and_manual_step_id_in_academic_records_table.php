<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsManualAndManualStepIdInAcademicRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('academic_records', function (Blueprint $table) {
            $table->tinyInteger('is_manual')->default(0)->nullable()->after('section_id');
            $table->foreign('manual_step_id')->references('id')->on('manual_steps');
            $table->unsignedBigInteger('manual_step_id')->after('is_manual')->nullable();
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
            $table->dropColumn('is_manual');
            $table->dropForeign(['manual_step_id']);
            $table->dropColumn('manual_step_id');
        });
    }
}
