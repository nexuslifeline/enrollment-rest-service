<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TranscriptRecordSubject extends Pivot
{
    protected $guarded = ['id'];
    protected $table = ['transcript_record_subjects'];
    // Note! should be refactored removed these fields since this is a Pivot Model
    protected $hidden = [
        'created_at',
        'deleted_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
