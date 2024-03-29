<?php

namespace App;

use App\Scopes\SchoolCategoryScope;
use App\Traits\OrderingTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Curriculum extends Model
{
    use SoftDeletes, OrderingTrait;
    protected $table = 'curriculums';
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at',
        'deleted_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function scopeSchoolCategoryFilter($query)
    {
        $user = Auth::user();

        if ($user->userable_type === 'App\Student') {
            return;
        }

        $userGroup = $user->userGroup()->first();
        Log::info($userGroup);
        if ($userGroup) {
            $schoolCategories = $userGroup->schoolCategories()->get()->pluck(['id']);
            return $query->whereHas('subjects', function($q) use ($schoolCategories) {
                return $q->whereIn('curriculum_subjects.school_category_id', $schoolCategories);
            });
        }
    }

    // protected static function boot()
    // {
    //     parent::boot();
    //     $user = Auth::user();

    //     if ($user->userable_type === 'App\Student') {
    //         return;
    //     }

    //     $userGroup = $user->userGroup()->first();
    //     if ($userGroup) {
    //         $schoolCategories = $userGroup->schoolCategories()->get()->pluck(['id']);
    //         static::addGlobalScope('school_categories', function (Builder $builder) use ($schoolCategories) {
    //             $builder->whereHas('schoolCategories', function($q) use ($schoolCategories) {
    //                 return $q->whereIn('school_category_id', $schoolCategories)
    //                     ->orWhereNull('school_category_id');
    //             });
    //         });
    //     }
    // }

    // public function schoolCategory()
    // {
    //     return $this->belongsTo('App\SchoolCategory');
    // }

    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    public function level()
    {
        return $this->belongsTo('App\Level');
    }

    public function subjects()
    {
        return $this->belongsToMany(
            'App\Subject',
            'curriculum_subjects',
            'curriculum_id',
            'subject_id'
        )->withPivot(['level_id','semester_id', 'course_id', 'school_category_id'])
        ->withTimestamps();
    }

    public function prerequisites()
    {
        return $this->belongsToMany(
            'App\Subject',
            'curriculum_prerequisites',
            'curriculum_id',
            'prerequisite_subject_id'
        )->withPivot(['subject_id'])->withTimestamps();
    }

    public function schoolCategories()
    {
        return $this->belongsToMany(
            'App\SchoolCategory',
            'curriculum_school_categories',
            'curriculum_id',
            'school_category_id'
        );
    }

    public function scopeWhereLike($query, $value)
    {
        return $query->where('curriculums.name', 'like', '%' . $value . '%')
            ->orWhere('curriculums.description', 'like', '%' . $value . '%')
            ->orWhere('curriculums.effective_year', 'like', '%' . $value . '%')
            ->orWhere('curriculums.notes', 'like', '%' . $value . '%')
            ->orWhereHas('course', function ($q) use ($value) {
                return $q->where('courses.name', $value);
            });
    }
}
