<?php

namespace App;

use App\Traits\OrderingTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

class Billing extends Model
{
    use SoftDeletes, OrderingTrait;
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at',
        'deleted_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $appends = ['total_amount_due', 'total_remaining_due'];

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
        return $this->payments->where('payment_status_id', 2)->sum('amount');
    }

    public function getTotalOverPayAttribute()
    {
        return $this->payments->where('payment_status_id', 2)->where('is_forwarded_overpay', 0)->sum('overpay');
    }

    public function term()
    {
        return $this->belongsTo('App\Term');
    }

    public function getSubmittedPaymentsAttribute() {
        $submittedStatus = 4;
        return  $this->payments->where('payment_status_id', $submittedStatus);
    }

    public function getTotalAmountDueAttribute() {
        return $this->total_amount + $this->previous_balance;
    }

    public function getTotalRemainingDueAttribute() {
        return $this->total_amount_due - $this->total_paid;
    }

    public function academicRecord() {
        return $this->belongsTo('App\AcademicRecord');
    }

    public function getPendingPaymentsCountAttribute() {
        $pending = Config::get('constants.payment_status.PENDING');
        return  $this->payments->where('payment_status_id', $pending)
            ->count();
    }

}
