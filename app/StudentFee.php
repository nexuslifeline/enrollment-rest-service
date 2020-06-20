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
        'App\StudentFeeItem',
        'student_fee_items',
        'student_fee_id',
        'school_fee_id'
      )->withPivot(['amount','notes']);
  }
}
