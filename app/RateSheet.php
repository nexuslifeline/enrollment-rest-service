<?php

namespace App;

use App\Level;
use App\Course;
use App\SchoolFee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RateSheet extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function fees()
    {
        return $this->belongsToMany(
            'App\SchoolFee',
            'rate_sheet_fees',
            'rate_sheet_id',
            'school_fee_id'
        )->withPivot(['amount','notes']);
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

}
