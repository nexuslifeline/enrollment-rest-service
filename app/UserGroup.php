<?php

namespace App;

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

    public function permissions()
    {
        return $this->belongsToMany(
            'App\Permission',
            'user_group_permissions',
            'user_group_id',
            'permission_id'
        )->withTimestamps();
    }

    public function schoolCategories()
    {
        return $this->belongsToMany(
            'App\SchoolCategory',
            'user_group_categories',
            'user_group_id',
            'school_category_id'
        )->withTimestamps();
    }
}
