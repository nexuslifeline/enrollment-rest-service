
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSectionForeignKeyOnTranscriptSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transcript_subjects', function (Blueprint $table) {
            $table->foreign('section_id')->references('id')->on('sections');
            $table->unsignedBigInteger('section_id')->after('subject_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transcript_subjects', function (Blueprint $table) {
            //
        });
    }
}
