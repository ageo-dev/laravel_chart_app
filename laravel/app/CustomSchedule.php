<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomSchedule extends Model
{
    protected $fillable = [
        'user_id','add_fund','show_datetime'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
