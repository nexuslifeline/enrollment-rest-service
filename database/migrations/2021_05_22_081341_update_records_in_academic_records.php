<?php

use App\Evaluation;
use App\AcademicRecord;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRecordsInAcademicRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('academic_records', function (Blueprint $table) {
            $academicRecords = AcademicRecord::withoutGlobalScopes()
                ->whereNull('level_id')
                ->whereNull('curriculum_id')
                ->get();
            foreach ($academicRecords as $item) {
                $evaluation = Evaluation::withoutGlobalScopes()
                    ->where('student_id', $item->student_id)
                    ->where('school_year_id', $item->school_year_id)
                    ->first();
                $item->update([
                    'level_id' => $evaluation->level_id,
                    'course_id' => $evaluation->course_id,
                    'semester_id' => $evaluation->semester_id,
                    'curriculum_id' => $evaluation->curriculum_id,
                    'student_curriculum_id' => $evaluation->student_curriculum_id,
                    'school_category_id' => $evaluation->school_category_id,
                    'evaluation_id' => $evaluation->id
                ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('academic_records', function (Blueprint $table) {
            //
        });
    }
}
