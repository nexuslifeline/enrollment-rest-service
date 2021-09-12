<?php

namespace App;

use App\Traits\OrderingTrait;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personnel extends Model
{
    use SoftDeletes, OrderingTrait;
    protected $guarded = ['id'];
    protected $appends = ['name'];
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

    public function education()
    {
        return $this->hasMany('App\PersonnelEducation');
    }

    public function employments()
    {
        return $this->hasMany('App\PersonnelEmployment');
    }

    public function studentGrades()
    {
        return $this->hasMany('App\StudentGrade');
    }

    public function schedules()
    {
        return $this->hasMany('App\SectionSchedule');
    }

    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->middle_name} {$this->last_name}";
    }

    public function scopeWhereLike($query, $value)
    {
        return $query->where('name', 'like', '%' . $value . '%')
            ->orWhere('first_name', 'like', '%' . $value . '%')
            ->orWhere('middle_name', 'like', '%' . $value . '%')
            ->orWhere('last_name', 'like', '%' . $value . '%')
            ->orWhereRaw('CONCAT(first_name, " ", coalesce(concat(middle_name, " "),""), last_name) like' .  "'%" .  $value  . "%'");
    }
}
