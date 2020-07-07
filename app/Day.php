<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    //
    public function times()
    {
        return $this->hasMany('App\Time');
    }
    public function coins()
    {
        return $this->hasMany('App\Coin');
    }
    public function weeks()
    {
        return $this->hasMany('App\Week');
    }
}
