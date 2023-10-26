<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'role', 'email', 'phone', 'password', 'address','employee_id','designation_lookup_id','joining_date','reporting_date'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile(){
        return $this->hasOne('App\Profile');
    }

    public function teamUser(){
        return $this->hasOne('App\TeamUser','user_id');
    }

    public function userRole(){
        return $this->hasOne('App\LookupData','lookup_pk_no','role');
    }

    public function designation(){
        return $this->hasOne('App\LookupData','lookup_pk_no','designation_lookup_id');
    }
}
