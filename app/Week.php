<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    //
    protected $table = "weeks";
    public function day()
    {
        return $this->belongsTo('App\Day' ,'id' , 'days_id');
    }
}
