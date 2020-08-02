<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastSchoolLevelIdOnEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->foreign('last_school_level_id')->references('id')->on('levels');
            $table->unsignedBigInteger('last_school_level_id')->after('last_school_year_to')->nullable();
        });
    }
}
