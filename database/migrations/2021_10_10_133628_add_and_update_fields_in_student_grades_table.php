<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAndUpdateFieldsInStudentGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_grades', function (Blueprint $table) {
            $table->date('rejected_date')->nullable()->after('finalized_date');
            $table->text('rejected_notes')->nullable()->after('rejected_date');
            $table->date('finalized_notes')->nullable()->after('finalized_date');
            $table->renameColumn('request_approved_date', 'edit_approved_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_grades', function (Blueprint $table) {
            $table->dropColumn('rejected_date');
            $table->dropColumn('rejected_notes');
            $table->dropColumn('finalized_notes');
            $table->renameColumn('edit_approved_date', 'request_approved_date');
        });
    }
}
