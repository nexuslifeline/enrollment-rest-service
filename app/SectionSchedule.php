<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class SectionSchedule extends Model
{
    // use SoftDeletes;
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at',
        'deleted_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function subject()
    {
        return $this->belongsTo('App\Subject');
    }

    public function personnel()
    {
        return $this->belongsTo('App\Personnel');
    }

    public function section()
    {
        return $this->belongsTo('App\Section');
    }
}
