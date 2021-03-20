<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentClearanceSignatory extends Model
{
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at',
        'deleted_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function studentClearance()
    {
        return $this->belongsTo('App\StudentClearance');
    }
}
