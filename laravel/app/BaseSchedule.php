<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseSchedule extends Model
{
    protected $fillable = [
        'add_fund','show_datetime','rank_id'
    ];

    public function rank() {
        return $this->belongsTo('App\Rank','rank_id','id');
    }
}
