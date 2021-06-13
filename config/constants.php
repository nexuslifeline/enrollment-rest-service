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
  ],
  'transcript_record_status' => [ // should be updated to ACTIVE, COMPLETED, SHIFTED
    'DRAFT' => 1,
    'FINALIZED' => 2,
    'PENDING' => 3
  ],
  'application_step' => [
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
];