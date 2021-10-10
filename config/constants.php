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
  ],
  'semesters' => [
    ['id' => 1, 'name' => '1st Sem', 'description' => 'First Semester'],
    ['id' => 2, 'name' => '2nd Sem', 'description' => 'Second Semester'],
    ['id' => 3, 'name' => '3rd Sem', 'description' => 'Third Semester'],
    ['id' => 4, 'name' => '4th Sem', 'description' => 'Summer']
  ],
  'levels' => [
    ['id' => 1, 'name' => 'Kinder 1'],
    ['id' => 2, 'name' => 'Kinder 2'],
    ['id' => 3, 'name' => 'Grade 1'],
    ['id' => 4, 'name' => 'Grade 2'],
    ['id' => 5, 'name' => 'Grade 3'],
    ['id' => 6, 'name' => 'Grade 4'],
    ['id' => 7, 'name' => 'Grade 5'],
    ['id' => 8, 'name' => 'Grade 6'],
    ['id' => 9, 'name' => 'Grade 7'],
    ['id' => 10, 'name' => 'Grade 8'],
    ['id' => 11, 'name' => 'Grade 9'],
    ['id' => 12, 'name' => 'Grade 10'],
    ['id' => 13, 'name' => 'Grade 11'],
    ['id' => 14, 'name' => 'Grade 12'],
    ['id' => 15, 'name' => 'First Year College'],
    ['id' => 16, 'name' => 'Second Year College'],
    ['id' => 17, 'name' => 'Third Year College'],
    ['id' => 18, 'name' => 'Fourth Year College'],
    ['id' => 19, 'name' => 'Fifth Year College'],
    ['id' => 20, 'name' => 'Masters Degree'],
    ['id' => 21, 'name' => 'Doctorate Degree'],
    ['id' => 22, 'name' => 'Short Term Program'],
    ['id' => 23, 'name' => 'Nursery'],
  ],
  'civil_status' => [
    'SINGLE' => 1,
    'MARRIED' => 2,
    'DIVORCED' => 3,
    'WIDOWED' => 4
  ],
  'student_grade_status' => [
    'DRAFT' => 1,
    'PUBLISHED' => 2,
    'SUBMITTED_FOR_REVIEW' => 3,
    'REQUEST_EDIT' => 4,
    'EDITING_APPROVED' => 5,
    'FINALIZED' => 6,
    'REJECTED' => 7
  ]
];