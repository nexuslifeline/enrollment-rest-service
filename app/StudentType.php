<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class StudentType extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $hidden = [
      'created_at',
      'deleted_at',
      'updated_at'
  ];
}
