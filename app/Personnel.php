<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personnel extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function user()
    {
        return $this->morphOne('App\User', 'userable');
    }
}
