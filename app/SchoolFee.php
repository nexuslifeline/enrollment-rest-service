<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolFee extends Model
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

    public function schoolFeeCategory()
    {
        return $this->belongsTo('App\SchoolFeeCategory');
    }

    public function studentFeeItems()
    {
        return $this->belongsToMany(
            'App\SchoolFee',
            'student_fee_items',
            'school_fee_id',
            'student_fee_id'
        )->withPivot(['amount', 'notes', 'is_initial_fee']);
    }
}
