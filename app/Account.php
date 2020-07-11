<?php

namespace App;

use App\Account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Account extends Model
{
    //
    protected $table = 'account';
    protected $fillable = [
        'id_str',
    ];

    public static function autoFollow()
    {


    }
}