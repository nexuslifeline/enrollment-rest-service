<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolFeeCategory extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at',
        'deleted_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function schooFeeCategory()
    {
        return $this->belongsTo('App\SchoolFeeCategory');
        //return $this->hasOne('App\SchoolFeeCategory');
    }
}
