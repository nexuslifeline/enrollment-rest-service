<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('code')->default('');
            $table->string('name')->default('')->nullable();
            $table->string('description')->default('')->nullable();
            $table->decimal('amount_per_unit', 13, 2)->default(0)->nullable();
            $table->decimal('amount_per_lab', 13, 2)->default(0)->nullable();
            $table->integer('units')->default(0)->nullable();
            $table->integer('labs')->default(0)->nullable();
            $table->integer('total_units')->default(0)->nullable();
            $table->decimal('total_amount', 13, 2)->default(0)->nullable();
            $table->unsignedBigInteger('school_category_id')->nullable();
            $table->foreign('school_category_id')->references('id')->on('school_categories');
            $table->integer('deleted_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('subjects');
    }
}
