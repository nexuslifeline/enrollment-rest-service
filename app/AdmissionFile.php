<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdmissionFile extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $hidden = [
        'deleted_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function admission()
    {
        return $this->belongsTo('App\Admission');
    }
}
