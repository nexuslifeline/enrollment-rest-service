<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsInBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->tinyInteger('is_forwarded')->default(0)->after('term_id');
            $table->text('system_notes')->after('is_forwarded')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->dropColumn('is_forwarded');
            $table->dropColumn('system_notes');
        });
    }
}
