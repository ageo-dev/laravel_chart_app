<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChargeLog extends Model
{
    protected $fillable = [
        'user_id','fund','charge_datetime'
    ];
}
