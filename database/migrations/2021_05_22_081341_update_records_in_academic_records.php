<?php

use App\Evaluation;
use App\AcademicRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            $academicRecords = DB::table('academic_records')
                ->whereNull('evaluation_id')
                ->get();

            foreach ($academicRecords as $item) {
                $evaluation = DB::table('evaluations')
                    ->where('student_id', $item->student_id)
                    ->where('school_year_id', $item->school_year_id)
                    ->first();

                DB::table('academic_records')->where('id', $item->id)
                    ->update([
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
