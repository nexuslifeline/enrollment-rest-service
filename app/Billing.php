<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Billing extends Model
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

    public function student()
    {
        return $this->belongsTo('App\Student');
    }

    public function schoolYear()
    {
        return $this->belongsTo('App\SchoolYear');
    }

    public function semester()
    {
        return $this->belongsTo('App\Semester');
    }

    public function studentFee()
    {
        return $this->belongsTo('App\StudentFee');
    }

    public function billingType()
    {
        return $this->belongsTo('App\BillingType');
    }

    public function billingItems()
    {
        return $this->hasMany('App\BillingItem');
    }

    public function payments()
    {
        return $this->hasMany('App\Payment');
    }

    public function getTotalPaidAttribute() {
        return $this->payments->sum('amount');
    }

}
