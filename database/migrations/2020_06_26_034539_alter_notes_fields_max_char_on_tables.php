<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterNotesFieldsMaxCharOnTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('admissions', function (Blueprint $table) {
            $table->dropColumn('approval_notes');
            $table->dropColumn('disapproval_notes');
        });

        Schema::table('admissions', function (Blueprint $table) {
            $table->string('approval_notes', 1255)->nullable()->after('admission_step_id');
            $table->string('disapproval_notes', 1255)->nullable()->after('approval_notes');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('approval_notes');
            $table->dropColumn('disapproval_notes');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->string('approval_notes', 1255)->nullable()->after('application_step_id');
            $table->string('disapproval_notes', 1255)->nullable()->after('approval_notes');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('notes');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('notes', 1255)->nullable()->after('payment_status_id');
        });

        Schema::table('payment_files', function (Blueprint $table) {
            $table->dropColumn('notes');
        });

        Schema::table('payment_files', function (Blueprint $table) {
            $table->string('notes', 1255)->nullable()->after('name');
        });

        Schema::table('rate_sheet_fees', function (Blueprint $table) {
            $table->dropColumn('notes');
        });

        Schema::table('rate_sheet_fees', function (Blueprint $table) {
            $table->string('notes', 1255)->nullable()->after('school_fee_id');
        });

        Schema::table('admission_files', function (Blueprint $table) {
            $table->dropColumn('notes');
        });

        Schema::table('admission_files', function (Blueprint $table) {
            $table->string('notes', 1255)->nullable()->after('name');
        });


        Schema::table('student_addresses', function (Blueprint $table) {
            $table->dropColumn('current_complete_address');
            $table->dropColumn('permanent_complete_address');
        });

        Schema::table('student_addresses', function (Blueprint $table) {
            $table->string('current_complete_address', 1255)->nullable()->after('current_postal_code');
            $table->string('permanent_complete_address', 1255)->nullable()->after('permanent_postal_code');
        });


        Schema::table('student_fee_items', function (Blueprint $table) {
            $table->dropColumn('notes');
        });

        Schema::table('student_fee_items', function (Blueprint $table) {
            $table->string('notes', 1255)->nullable()->after('school_fee_id');
        });
    }
}
