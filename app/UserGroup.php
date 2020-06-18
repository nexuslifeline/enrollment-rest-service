<?php

namespace App;

use App\UserGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserGroup extends Model
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
}
