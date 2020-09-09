<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at',
        'deleted_at',
        'updated_at'
    ];

    public function permissionGroup()
    {
        return $this->belongsTo('App\PermissionGroup');
    }
}
