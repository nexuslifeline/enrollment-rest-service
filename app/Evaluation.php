<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evaluation extends Model
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

    public function files()
    {
        return $this->hasMany('App\EvaluationFile');
    }

    public function subjects()
    {
        return $this->belongsToMany('App\Subject', 'evaluation_subjects', 'evaluation_id', 'subject_id')
                ->withPivot('level_id','semester_id')
                ->withTimestamps();
    }
}
