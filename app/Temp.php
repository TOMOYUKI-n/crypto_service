<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Tweet;
use App\Coin;
use App\Time;
use App\Week;
use App\Day;
use App\Hour;
use App\Minute;
use App\Account;
use App\Temp;
use GuzzleHttp\Client;

class Temp extends Model
{
    //
    protected $table = 'temp';
    protected $fillable = [
        'account_id'
    ];
}
