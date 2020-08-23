<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    //
    protected $table = "weeks";
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
    public function day()
    {
        return $this->belongsTo('App\Day' ,'id' , 'days_id');
    }
}
