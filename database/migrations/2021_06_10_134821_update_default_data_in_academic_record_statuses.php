<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDefaultDataInAcademicRecordStatuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
        DB::table('academic_record_statuses')->where(['id' => 5])->update(['name' => 'Enlistment Pending', 'description' => 'Enlistment Pending']);
        DB::table('academic_record_statuses')->where(['id' => 6])->update(['name' => 'Enlistment Rejected', 'description' => 'Enlistment Rejected']);
        DB::table('academic_record_statuses')->where(['id' => 7])->update(['name' => 'Enlistment Approved', 'description' => 'Enlistment Approved']);
        DB::table('academic_record_statuses')->where(['id' => 8])->update(['name' => 'Assessment Rejected', 'description' => 'Assessment Rejected']);
        DB::table('academic_record_statuses')->where(['id' => 9])->update(['name' => 'Assessment Approved', 'description' => 'Assessment Approved']);
        DB::table('academic_record_statuses')->where(['id' => 10])->update(['name' => 'Payment Submitted', 'description' => 'Payment Submitted']);
        DB::table('academic_record_statuses')->insert([
            [
                'name' => 'Enrolled',
                'description' => 'Enrolled'
            ]
        ]);
    }
}
