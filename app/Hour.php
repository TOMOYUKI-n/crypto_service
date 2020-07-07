<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use App\Tweet;

class Hour extends Model
{
    //
    protected $fillable = [
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
    public function time()
    {
        return $this->belongsTo('App\Time' ,'id' , 'times_id');
    }
}
