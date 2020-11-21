<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStudentDocumentTypeForeignKeyOnStudentFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_files', function (Blueprint $table) {
            $table->foreign('student_id')->references('id')->on('students');
            $table->unsignedBigInteger('student_id')->after('path')->nullable();
            $table->foreign('document_type_id')->references('id')->on('document_types');
            $table->unsignedBigInteger('document_type_id')->after('student_id')->nullable();
        });
    }

    public function down()
    {
        Schema::dropColumn(['student_id', 'document_type_id']);
    }


}
