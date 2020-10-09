<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentFeeTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_fee_terms', function (Blueprint $table) {
            $table->id();
            $table->foreign('student_fee_id')->references('id')->on('student_fees');
            $table->unsignedBigInteger('student_fee_id')->nullable();
            $table->foreign('term_id')->references('id')->on('terms');
            $table->unsignedBigInteger('term_id')->nullable();
            $table->decimal('amount', 13, 2)->default(0)->nullable();
            $table->tinyInteger('is_billed')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billing_terms');
    }
}
