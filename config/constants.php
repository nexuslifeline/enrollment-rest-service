<?php

return [
  'academic_record_status' => [
    'DRAFT' => 1,
    'EVALUATION_PENDING' => 2,
    'EVALUATION_REJECTED' => 3,
    'EVALUATION_APPROVED' => 4,
    'ENLISTMENT_PENDING' => 5,
    'ENLISTMENT_REJECTED' => 6,
    'ENLISTMENT_APPROVED' => 7,
    'ASSESSMENT_REJECTED' => 8,
    'ASSESSMENT_APPROVED' => 9,
    'PAYMENT_SUBMITTED' => 10,
    'ENROLLED' => 11,
    'CLOSED' => 12
  ],
  'transcript_record_status' => [ // should be updated to ACTIVE, COMPLETED, SHIFTED
    'DRAFT' => 1,
    'FINALIZED' => 2,
    'PENDING' => 3
  ],
  'onboarding_step' => [
    'PROFILE' => 1,
    'ADDRESS' => 2,
    'FAMILY' => 3, 
    'EDUCATION' => 4,
    'REQUEST_EVALUATION' => 5,
    'EVALUATION_IN_REVIEW' => 6,
    'ACADEMIC_RECORD_APPLICATION' => 7,
    'ACADEMIC_RECORD_IN_REVIEW' => 8,
    'PAYMENTS' => 9,
    'PAYMENT_IN_REVIEW' => 10
  ],
  'evaluation_status' => [
    'PENDING' => 1,
    'SUBMITTED' => 2,
    'APPROVED' => 3,
    'REJECTED' => 4,
    'COMPLETED' => 5
  ],
  'billing_type' => [
    'INITIAL_FEE' => 1,
    'SOA' => 2,
    'BILL' => 3
  ],
  'billing_status' => [
    'PAID' => 1,
    'UNPAID' => 2,
    'PARTIALLY_PAID' => 3
  ],
  'payment_status' => [
    'DRAFT' => 1, // before this is Pending
    'APPROVED' => 2,
    'REJECTED' => 3,
    'PENDING' => 4 // before this is Submitted
  ],
  'payment_mode' => [
    'BANK' => 1,
    '7ELEVEN' => 2,
    'OTHERS' => 3,
    'EWALLET' => 4,
    'PERA_PADALA' => 5,
    'CASH' => 6,
    'CHECK' => 7
  ],
  'student_type' => [
    'REGULAR' => 1,
    'IRREGULAR' => 2
  ]
];