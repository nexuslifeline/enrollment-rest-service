<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait OrderingTrait
{
  public function scopeOrderByStudent($query, $orderBy, $sort)
  {
    $addressFields = ['complete_address', 'city', 'barangay', 'region', 'province'];
    if ($this->getTable() !== 'students') {
      $query->leftJoin('students', 'students.id', '=', 'student_id');
    }
    if (!in_array($orderBy, $addressFields)) {
      return $query->orderBy('students.' . $orderBy, $sort);
    } else {
      return $query->leftJoin('student_addresses', 'student_addresses.student_id', '=', 'students.id')
        ->orderBy('student_addresses.current_'.$orderBy, $sort);
    }
  }

  public function scopeOrderByLevel($query, $orderBy, $sort)
  {
    $orderBy = $orderBy === 'level_name' ? 'name' : $orderBy;
    $query->leftJoin('levels', 'levels.id', '=', 'level_id')
      ->orderBy('levels.'.$orderBy, $sort);
  }

  public function scopeOrderByCourse($query, $orderBy, $sort)
  {
    $orderBy = $orderBy === 'course_name' ? 'name' : $orderBy;
    return $query->leftJoin('courses', 'courses.id', '=', 'course_id')
      ->orderBy('courses.'.$orderBy, $sort);
  }

  public function scopeOrderByStudentCategory($query, $orderBy, $sort)
  {
    $orderBy = $orderBy === 'student_category_name' ? 'name' : $orderBy;
    return $query->leftJoin('student_categories', 'student_categories.id', '=', 'student_category_id')
    ->orderBy('student_categories.'.$orderBy, $sort);
  }

  public function scopeOrderBySchoolCategory($query, $orderBy, $sort)
  {
    $orderBy = $orderBy === 'school_category_name' ? 'name' : $orderBy;
    if ($this->getTable() == 'curriculums') {
      // $query->join('curriculum_subjects', 'curriculum_subjects.curriculum_id', '=', 'curriculums.id')
      // ->leftJoin('school_categories', 'school_categories.id', '=', 'school_category_id')
      // ->orderBy('school_categories.' . $orderBy, $sort)
      // ->groupBy('curriculums.id');
    } else {
      return $query->leftJoin('school_categories', 'school_categories.id', '=', 'school_category_id')
        ->orderBy('school_categories.' . $orderBy, $sort);
    }
  }

  public function scopeOrderByUserGroup($query, $orderBy, $sort)
  {
    $orderBy = $orderBy === 'user_group_name' ? 'name' : $orderBy;
    return $query->leftJoin('users', 'users.userable_id', '=', 'personnels.id')
    ->leftJoin('user_groups', 'users.id', '=', 'user_group_id')
    ->orderBy('user_groups.'.$orderBy, $sort);
  }

  public function scopeOrderByDepartment($query, $orderBy, $sort)
  {
    $orderBy = $orderBy === 'department_name' ? 'name' : $orderBy;
    return $query->leftJoin('departments', 'departments.id', '=', 'department_id')
    ->orderBy('departments.'.$orderBy, $sort);
  }

  public function scopeOrderByBillingStatus($query, $orderBy, $sort)
  {
    $orderBy = $orderBy === 'billing_status_name' ? 'name' : $orderBy;
    return $query->leftJoin('billing_statuses', 'billing_statuses.id', '=', 'billing_status_id')
    ->orderBy('billing_statuses.' . $orderBy, $sort);
  }

  public function scopeOrderByBillingType($query, $orderBy, $sort)
  {
    $orderBy = $orderBy === 'billing_type_name' ? 'name' : $orderBy;
    return $query->leftJoin('billing_types', 'billing_types.id', '=', 'billing_type_id')
    ->orderBy('billing_types.' . $orderBy, $sort);
  }

  public function scopeOrderBySemester($query, $orderBy, $sort)
  {
    $orderBy = $orderBy === 'semester_name' ? 'name' : $orderBy;
    return $query->leftJoin('semesters', 'semesters.id', '=', 'semester_id')
    ->orderBy('semesters.' . $orderBy, $sort);
  }
}
