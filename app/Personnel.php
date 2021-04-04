<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personnel extends Model
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

    public function user()
    {
        return $this->morphOne('App\User', 'userable');
    }

    public function photo()
    {
        return $this->hasOne('App\PersonnelPhoto');
    }

    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->middle_name} {$this->last_name}";
    }
}
