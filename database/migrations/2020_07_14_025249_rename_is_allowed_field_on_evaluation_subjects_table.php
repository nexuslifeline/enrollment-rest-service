<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameIsAllowedFieldOnEvaluationSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evaluation_subjects', function (Blueprint $table) {
            $table->renameColumn('is_allowed', 'is_taken');
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
            $table->renameColumn('is_taken', 'is_allowed');
        });
    }
}
