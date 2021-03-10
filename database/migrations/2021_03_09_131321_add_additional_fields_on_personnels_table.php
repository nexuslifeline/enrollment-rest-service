<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalFieldsOnPersonnelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personnels', function (Blueprint $table) {
            $table->string('emergency_mobile_no')->after('birth_date')->default('')->nullable();
            $table->string('emergency_phone_no')->after('birth_date')->default('')->nullable();
            $table->unsignedBigInteger('personnel_status_id')->after('birth_date')->default(1)->nullable();
            $table->foreign('personnel_status_id')->references('id')->on('personnel_statuses');
            $table->string('job_title')->after('birth_date')->default('')->nullable();
            $table->unsignedBigInteger('department_id')->after('birth_date')->nullable();
            $table->foreign('department_id')->references('id')->on('departments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personnels', function (Blueprint $table) {
            //
        });
    }
}
