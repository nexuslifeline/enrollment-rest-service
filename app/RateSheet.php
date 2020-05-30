<?php

namespace App;

use App\Level;
use App\Course;
use App\RateSheetItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RateSheet extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function items()
    {
        return $this->hasMany('App\RateSheetItem');
    }

    public function level()
    {
        return $this->belongsTo('App\Level');
    }

    public function course()
    {
        return $this->belongsTo('App\Course');
    }

}
