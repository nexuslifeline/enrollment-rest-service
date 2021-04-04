<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class EnrollmentClearData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear-enrollment-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all the data related to student.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('student_addresses')->truncate();
        DB::table('student_families')->truncate();
        DB::table('student_files')->truncate();
        DB::table('student_photos')->truncate();
        DB::table('student_previous_educations')->truncate();
        DB::table('evaluations')->truncate();
        DB::table('transcript_record_subjects')->truncate();
        DB::table('transcript_records')->truncate();
        DB::table('academic_record_subjects')->truncate();
        DB::table('academic_records')->truncate();
        DB::table('admission_files')->truncate();
        DB::table('admissions')->truncate();
        DB::table('applications')->truncate();
        DB::table('billing_items')->truncate();
        DB::table('billing_terms')->truncate();
        DB::table('billings')->truncate();
        DB::table('student_fee_items')->truncate();
        DB::table('student_fee_terms')->truncate();
        DB::table('student_fees')->truncate();
        DB::table('payment_files')->truncate();
        DB::table('payment_receipt_files')->truncate();
        DB::table('payments')->truncate();
        DB::table('students')->truncate();
        DB::table('student_clearances')->truncate();
        DB::table('student_clearance_signatories')->truncate();
        DB::table('student_grades')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::statement('SET SQL_SAFE_UPDATES = 0;');
        DB::table('users')->where('userable_type', "App\\Student")->delete();
        DB::statement('SET SQL_SAFE_UPDATES = 1;');
    }
}
