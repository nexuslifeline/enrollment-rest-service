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
        // 1 Draft
        // 2 Evaluation Pending
        // 3 Evaluation Rejected
        // 4 Evaluation Approved
        // 5 Enlistment Rejected
        // 6 Enlistment Approved
        // 7 Assessment Rejected
        // 8 Assessment Approved
        // 9 Payment Submitted
        // 10 Enrolled
        DB::table('academic_record_statuses')->where(['id' => 2])->update(['name' => 'Evaluation Pending', 'description' => 'Evaluation Pending']);
        DB::table('academic_record_statuses')->where(['id' => 3])->update(['name' => 'Evaluation Rejected', 'description' => 'Evaluation Rejected']);
        DB::table('academic_record_statuses')->insert([
            [
                'name' => 'Evaluation Approved',
                'description' => 'Evaluation Approved'
            ],
            [
                'name' => 'Enlistment Rejected',
                'description' => 'Enlistment Rejected'
            ],
            [
                'name' => 'Enlistment Approved',
                'description' => 'Enlistment Approved'
            ],
            [
                'name' => 'Assessment Rejected',
                'description' => 'Assessment Rejected'
            ],
            [
                'name' => 'Assessment Approved',
                'description' => 'Assessment Approved'
            ],
            [
                'name' => 'Payment Submitted',
                'description' => 'Payment Submitted'
            ],
            [
                'name' => 'Enrolled',
                'description' => 'Enrolled'
            ],
        ]);
    }
}
