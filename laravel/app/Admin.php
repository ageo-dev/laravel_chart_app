<?php

namespace App;
 
use Illuminate\Foundation\Auth\User as Authenticatable;
 
class Admin extends Authenticatable
{
 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'password', 'memo','role_id'
    ];
 
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role() {
        return $this->belongsTo('App\Role','role_id','id');
    }
}