<?php

namespace App;

use App\Student;
use App\Personnel;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'user_group_id',
        'userable_id',
        'userable_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
        'email_verified_at',
        //'userable_id',
        // 'created_at',
        'deleted_at',
        // 'updated_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function userable()
    {
        return $this->morphTo();
    }

    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }

    public function student()
    {
        return $this->belongsTo('App\Student');
    }

    public function personnel()
    {
        return $this->belongsTo('App\Personnel');
    }

    public function userGroup()
    {
        return $this->belongsTo('App\UserGroup');
    }
}
