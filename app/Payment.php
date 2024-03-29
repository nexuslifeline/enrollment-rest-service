<?php

namespace App;

use App\Scopes\SchoolCategoryScope;
use App\Traits\OrderingTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    //
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

    // temporarily commented this, because we need to add first school category in this
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::addGlobalScope(new SchoolCategoryScope);
    // }

    public function files()
    {
        return $this->hasMany('App\PaymentFile');
    }

    public function paymentReceiptFiles()
    {
        return $this->hasMany('App\PaymentReceiptFile');
    }

    public function paymentMode()
    {
      return $this->belongsTo('App\PaymentMode');
    }

    public function student()
    {
        return $this->belongsTo('App\Student');
    }

    public function billing()
    {
        return $this->belongsTo('App\Billing');
    }

    public function scopeWhereLike($query, $value)
    {
        return $query->where('date_paid', 'like', '%' . $value . '%')
            ->orWhere('transaction_no', 'like', '%' . $value . '%')
            ->orWhere('reference_no', 'like', '%' . $value . '%')
            ->orWhere('amount', 'like', '%' . $value . '%')
            ->orWhere('notes', 'like', '%' . $value . '%')
            ->orWhere('approval_notes', 'like', '%' . $value . '%')
            ->orWhere('disapproval_notes', 'like', '%' . $value . '%')
            ->orWhere('reference_no', 'like', '%' . $value . '%');
    }
}
