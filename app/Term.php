<?php

namespace App;

use App\Scopes\SchoolCategoryScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Term extends Model
{
    //
    use SoftDeletes;
    protected $guarded = [];
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

        static::addGlobalScope(new SchoolCategoryScope);
    }

    public function schoolYear()
    {
        return $this->belongsTo('App\SchoolYear');
    }

    public function schoolCategory()
    {
        return $this->belongsTo('App\SchoolCategory');
    }

    public function semester()
    {
        return $this->belongsTo('App\Semester');
    }

    public function studentFees()
    {
        return $this->belongsToMany('App\StudentFee', 'student_fee_terms', 'term_id', 'student_fee_id')
            ->withPivot(['amount', 'is_billed']);
    }

    public function billing()
    {
        return $this->hasOne('App\Billing');
    }

    public function getPreviousBalanceAttribute()
    {
        return StudentFee::find($this->pivot->student_fee_id)->getPreviousBalance();
    }
}
