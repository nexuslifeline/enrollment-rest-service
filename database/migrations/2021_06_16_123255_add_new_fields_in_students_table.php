<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsInStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->unsignedBigInteger('onboarding_step_id')->nullable()->after('is_manual')->default(1);
            $table->foreign('onboarding_step_id')->references('id')->on('onboarding_steps');
            $table->tinyInteger('is_onboarding')->default(0)->nullable()->after('onboarding_step_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('onboarding_step_id');
            $table->dropColumn('is_onboarding');
        });
    }
}
