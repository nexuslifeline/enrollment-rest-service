<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentFamily extends Model
{
    use SoftDeletes;
    protected $guarded = [];
}
