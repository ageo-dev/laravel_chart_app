<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','rank_id', 
        'custom_per','custom_per_flag','first_fund','total_fund',
        'now_fund','investment_count','start_date','log_done_date','memo',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function customSchedule()
    {
        return $this->hasMany('App\CustomSchedule');
    }

    public function rank() {
        return $this->belongsTo('App\Rank','rank_id','id');
    }
}
