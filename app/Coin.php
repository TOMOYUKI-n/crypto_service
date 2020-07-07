<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use App\Tweet;

class Coin extends Model
{
    //
    protected $fillable = [
        'high','low'
    ];
    public function day()
    {
        return $this->belongsTo('App\Day' ,'id' , 'days_id');
    }



}
