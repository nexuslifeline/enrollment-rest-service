<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentClearanceSignatoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_clearance_signatories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_clearance_id')->nullable();
            $table->foreign('student_clearance_id')->references('id')->on('student_clearances');
            $table->unsignedBigInteger('personnel_id')->nullable();
            $table->foreign('personnel_id')->references('id')->on('personnels');
            $table->tinyInteger('is_cleared')->nullable()->default(0);
            $table->dateTime('date_cleared')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('student_clearance_signatories');
    }
}
