<?php

namespace App;

use App\Scopes\SchoolCategoryScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    //
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
}
