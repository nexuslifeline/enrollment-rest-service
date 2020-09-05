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
      )->withPivot(['amount','notes']);
  }

  public function billings()
  {
      return $this->hasMany('App\Billing');
  }

  public function student()
  {
    return $this->belongsTo('App\Student');
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
}
