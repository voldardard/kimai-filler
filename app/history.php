<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class history extends Model
{
    protected $fillable = [
        'users_id', 'event_id', 'event_begin', 'event_duration'
    ];

}
