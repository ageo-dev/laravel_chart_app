<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppConfig extends Model
{
    protected $fillable = [
        'default_per','update_hour','update_minute',
        'list_paginate','database_update'
    ];
}
