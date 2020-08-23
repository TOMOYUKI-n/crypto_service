<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Tweet;

class Minute extends Model
{
    //
    protected $fillable = [
        'datetime',
        'BTC',
        'ETH',
        'ETC',
        'LSK',
        'FCT',
        'XRP',
        'XEM',
        'LTC',
        'BCH',
        'MONA',
        'XLM',
        'QTUM',
        'DASH',
        'ZEC',
        'XMR',
        'REP',
    ];
}
