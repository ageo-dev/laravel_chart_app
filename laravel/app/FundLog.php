<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FundLog extends Model
{
    protected $fillable = [
        'user_id','fund','show_datetime','type','base_fund'
    ];
}
