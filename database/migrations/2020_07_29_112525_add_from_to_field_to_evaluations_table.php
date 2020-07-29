<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFromToFieldToEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->string('last_school_year_from')->after('last_year_attended')->default('')->nullable();
            $table->string('last_school_year_to')->after('last_school_year_from')->default('')->nullable();
        });
    }


}
