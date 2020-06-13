<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class StudentFeeItem extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
}
