<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TranscriptRecordSubject extends Model
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
}
