<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentAddress extends Model
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

    /* Get the student that owns the student address */
    public function student()
    {
        return $this->belongsTo('App\Student');
    }

    public function currentCountry()
    {
        return $this->belongsTo('App\Country', 'current_country_id', 'id');
    }

    public function permanentCountry()
    {
        return $this->belongsTo('App\Country', 'permanent_country_id', 'id');
    }

}
