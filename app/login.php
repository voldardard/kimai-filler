<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class login extends Model
{
    protected $fillable = [
        'users_id', 'token', 'revocation',
    ];

}
