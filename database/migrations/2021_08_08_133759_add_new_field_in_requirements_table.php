<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldInRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requirements', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->unsignedBigInteger('document_type_id')->nullable()->after('id');
            $table->foreign('document_type_id')->references('id')->on('document_types');
        });
    }

    /**\
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requirements', function (Blueprint $table) {
            $table->string('name')->default('')->nullable()->after('id');
            $table->dropForeign(['document_type_id']);
            $table->dropColumn('document_type_id');
        });
    }
}
