<?php

namespace App;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class StudentFee extends Model
{
  use SoftDeletes;
  protected $guarded = ['id'];

  public function studentFeeItems()
  {
      return $this->belongsToMany(
        'App\SchoolFee',
        'student_fee_items',
        'student_fee_id',
        'school_fee_id'
      )->withPivot(['amount','notes','is_initial_fee']);
  }

  public function billings()
  {
      return $this->hasMany('App\Billing');
  }

  public function student()
  {
    return $this->belongsTo('App\Student');
  }

  public function academicRecord()
  {
    return $this->belongsTo('App\AcademicRecord');
  }

  public function level()
  {
    return $this->belongsTo('App\Level');
  }

  public function course()
  {
    return $this->belongsTo('App\Course');
  }

  public function semester()
  {
    return $this->belongsTo('App\Semester');
  }

  public function schoolYear()
  {
    return $this->belongsTo('App\SchoolYear');
  }

  public function terms()
  {
    return $this->belongsToMany('App\Term', 'student_fee_terms', 'student_fee_id', 'term_id')
    ->withPivot(['amount', 'is_billed']);;
  }

  public function recomputeTerms($payment = 0)
  {
    $terms = Term::where('school_year_id', $this->school_year_id)
        ->where('school_category_id', $this->academicRecord->school_category_id)
        ->where('semester_id', $this->semester_id)
        ->get();

    if (count($terms) > 0) {
        $studentFeeTerms = [];
        $amount = ($this->total_amount - $payment) / count($terms);
        foreach ($terms as $term) {
            $studentFeeTerms[$term->id] = [
                'amount' => $amount
            ];
        }

        $this->terms()->sync($studentFeeTerms);
    }
  }

  public function getPreviousBalance()
  {
    $totalBilling = Billing::where('student_id', $this->student_id)
        ->where('billing_type_id', 2)
        ->get()
        ->sum('total_amount');
    $totalPayment = Payment::where('student_id', $this->student_id)
        ->whereHas('billing', function ($query) {
            return $query->where('billing_type_id', 2);
        })
        ->get()
        ->sum('amount');
    return $totalBilling - $totalPayment;
  }
}
