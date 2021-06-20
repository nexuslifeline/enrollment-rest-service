<?php

namespace App;

use App\Scopes\SchoolCategoryScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

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

    protected static function boot()
    {
        parent::boot();

        $user = Auth::user();

        if (!$user || $user->userable_type === 'App\Student') {
            return;
        }

        $userGroup = $user->userGroup()->first();
        if ($userGroup) {
            $schoolCategories = $userGroup->schoolCategories()->get()->pluck(['id']);
            static::addGlobalScope('school_category', function (Builder $builder) use ($schoolCategories) {
                $builder->whereHas('academicRecord', function ($q) use ($schoolCategories) {
                    return $q->whereIn('school_category_id', $schoolCategories)
                        ->orWhereNull('school_category_id');
                });
            });
        }
    }

    public function student()
    {
        return $this->belongsTo('App\Student');
    }

    public function studentCategory()
    {
        return $this->belongsTo('App\StudentCategory');
    }

    public function lastSchoollevel()
    {
        return $this->belongsTo('App\Level', 'last_school_level_id');
    }

    public function academicRecord()
    {
        return $this->belongsTo('App\AcademicRecord');
    }


    // public function subjects()
    // {
    //     return $this->belongsToMany(
    //         'App\Subject',
    //         'evaluation_subjects',
    //         'evaluation_id',
    //         'subject_id'
    //     )->withPivot([
    //       'level_id',
    //       'semester_id',
    //       'grade',
    //       'notes',
    //       'is_taken'
    //     ])->withTimestamps();
    // }
}
