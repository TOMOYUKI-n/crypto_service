<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Tweet;

class Account extends Model
{
    //
    protected $table = 'account';
    protected $fillable = [
        'id_str',
    ];

    public static function autoFollow($userid)
    {
        //$targetId = '1252388152221655049';//accountD
        //$userid = '1284754391442968578';//もんぶらん

    }
}
