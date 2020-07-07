<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use App\Tweet;
use Carbon\Carbon;

class Time extends Model
{
    //
    protected $fillable = [
        'get_dates',
    ];
    /*
    public function coins()
    {
        return $this->hasOne('App\Coin', 'times_id', 'id');
    }
    */
    public function day()
    {
        return $this->belongsTo('App\Coin', 'days_id', 'id');
    }
    public function times()
    {
        return $this->hasmany('App\Time' ,'id' , 'times_id');
    }


}
