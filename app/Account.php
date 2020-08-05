<?php

namespace App;

use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Account extends Model
{
    //
    protected $table = 'account';
    protected $fillable = [
        'id_str',
    ];


}
