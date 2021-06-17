<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultDataInAcademicRecordStatuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('academic_record_statuses')->truncate();
        // 'DRAFT' => 1,
        // 'EVALUATION_PENDING' => 2,
        // 'EVALUATION_REJECTED' => 3,
        // 'EVALUATION_APPROVED' => 4,
        // 'ENLISTMENT_PENDING' => 5, ///////////
        // 'ENLISTMENT_REJECTED' => 6,
        // 'ENLISTMENT_APPROVED' => 7,
        // 'ASSESSMENT_REJECTED' => 8,
        // 'ASSESSMENT_APPROVED' => 9,
        // 'PAYMENT_SUBMITTED' => 10,
        // 'ENROLLED' => 11,
        // 'CLOSED' => 12
        DB::table('academic_record_statuses')->insert([
            ['name' => 'Draft', 'description' => 'Draft'],
            ['name' => 'Evaluation Pending', 'description' => 'Evaluation Pending'],
            ['name' => 'Evaluation Rejected', 'description' => 'Evaluation Rejected'],
            ['name' => 'Evaluation Approved', 'description' => 'Evaluation Approved'],
            ['name' => 'Enlistment Pending', 'description' => 'Enlistment Pending'],
            ['name' => 'Enlistment Rejected', 'description' => 'Enlistment Rejected'],
            ['name' => 'Enlistment Approved', 'description' => 'Enlistment Approved'],
            ['name' => 'Assessment Rejected', 'description' => 'Assessment Rejected'],
            ['name' => 'Assessment Approved', 'description' => 'Assessment Approved'],
            ['name' => 'Payment Submitted', 'description' => 'Payment Submitted'],
            ['name' => 'Enrolled', 'description' => 'Enrolled'],
            ['name' => 'Closed', 'description' => 'Closed'],
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
