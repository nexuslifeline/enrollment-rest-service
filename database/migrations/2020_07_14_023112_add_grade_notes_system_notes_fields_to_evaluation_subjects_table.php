<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGradeNotesSystemNotesFieldsToEvaluationSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evaluation_subjects', function (Blueprint $table) {
            $table->decimal('grade', 13, 2)->after('subject_id')->default(0)->nullable();
            $table->text('notes')->after('grade')->nullable();
            $table->text('system_notes')->after('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evaluation_subjects', function (Blueprint $table) {
            $table->dropColumn('grade');
            $table->dropColumn('notes');
            $table->dropColumn('system_notes');
        });
    }
}
