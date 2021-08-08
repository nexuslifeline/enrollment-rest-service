<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requirement extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected $hidden = [
        'created_at',
        'deleted_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function schoolCategory()
    {
        return $this->belongsTo('App\SchoolCategory');
    }

    public function documentType()
    {
        return $this->belongsTo('App\DocumentType');
    }

    public function getIsSubmittedAttribute()
    {
        return $this->attributes['is_submitted'];
    }

    public function setIsSubmittedAttribute($value)
    {
        $this->attributes['is_submitted'] = $value;
    }
}
