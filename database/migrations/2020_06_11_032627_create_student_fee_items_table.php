<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentFeeItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_fee_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_fee_id')->nullable();
            $table->foreign('student_fee_id')->references('id')->on('student_fees');
            $table->unsignedBigInteger('school_fee_id')->nullable();
            $table->foreign('school_fee_id')->references('id')->on('school_fees');
            $table->string('notes')->default('')->nullable();
            $table->decimal('amount', 13, 2)->default(0)->nullable();
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
        Schema::dropIfExists('student_fee_items');
    }
}
