<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluation_files', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('')->nullable();
            $table->text('notes')->nullable();
            $table->string('hash_name')->default('')->nullable();
            $table->string('path')->default('')->nullable();
            $table->foreign('evaluation_id')->references('id')->on('evaluations');
            $table->unsignedBigInteger('evaluation_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluation_files');
    }
}
