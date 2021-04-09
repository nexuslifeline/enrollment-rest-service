<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdditionalFieldsOnPersonnelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personnels', function (Blueprint $table) {
            $table->string('website_url')->after('emergency_mobile_no')->default('')->nullable();
            $table->string('instagram_url')->after('emergency_mobile_no')->default('')->nullable();
            $table->string('twitter_url')->after('emergency_mobile_no')->default('')->nullable();
            $table->string('linkedin_url')->after('emergency_mobile_no')->default('')->nullable();
            $table->string('facebook_url')->after('emergency_mobile_no')->default('')->nullable();
            $table->text('biography')->after('emergency_mobile_no')->nullable();
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
            $table->dropColumn(['website_url', 'instagram_url', 'twitter_url', 'linkedin_url', 'facebook_url', 'biography']);
        });
    }
}
