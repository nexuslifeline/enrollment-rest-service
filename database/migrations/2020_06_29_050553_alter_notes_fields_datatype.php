<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterNotesFieldsDatatype extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function ($table) {
            $table->text('notes')->default(null)->change();
            $table->text('approval_notes')->default(null)->change();
            $table->text('disapproval_notes')->default(null)->change();
        });

        Schema::table('admissions', function (Blueprint $table) {
            $table->text('approval_notes')->default(null)->change();
            $table->text('disapproval_notes')->default(null)->change();
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->text('approval_notes')->default(null)->change();
            $table->text('disapproval_notes')->default(null)->change();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->text('notes')->default(null)->change();
        });

        Schema::table('payment_files', function (Blueprint $table) {
            $table->text('notes')->default(null)->change();
        });

        Schema::table('rate_sheet_fees', function (Blueprint $table) {
            $table->text('notes')->default(null)->change();
        });

        Schema::table('admission_files', function (Blueprint $table) {
            $table->text('notes')->default(null)->change();
        });

        Schema::table('student_fee_items', function (Blueprint $table) {
            $table->text('notes')->default(null)->change();
        });

    }
}
