<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameMajorColumnOnCurriculumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('curriculums', function (Blueprint $table) {
            $table->renameColumn('major', 'description');
        });

        Schema::table('curriculums', function (Blueprint $table) {
          $table->string('description', 1255)->default('')->change();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('curriculums', function (Blueprint $table) {
            $table->renameColumn('description', 'major');
        });
    }
}
