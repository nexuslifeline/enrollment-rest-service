<?php

namespace App;

use App\RateSheet;
use App\SchoolFee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RateSheetItem extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function rateSheet()
    {
        return $this->belongsTo('App\RateSheet');
    }

    public function schoolFee()
    {
        return $this->belongsTo('App\SchoolFee');
    }
}
