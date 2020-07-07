<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Log;
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
use GuzzleHttp\Client;

class Account extends Model
{
    //
    protected $table = 'account';
    protected $fillable = [
        'id_str'
    ];

    // ==========================================================================================
    // ユーザー情報をAPIにて取得する !!!!!!調整中!!!!!!!
    // ==========================================================================================
    public static function GetUsersInfo()
    {
        /**
         * 1日1回更新されるユーザーDBから ユーザー名またはプロフィールに仮想通貨が含まれているユーザーを抽出
         * 100件ずつしかapiで取得できない
         * 1日MAX1000件までのフォロー-> 100件*10回=1000件　よってn=10
         */
        $account_scorp = Temp::select()->get();
        for( $n =0; $n < count($account_scorp); $n++ )
        {
            $user_id[$n] = $account_scorp[$n]["id_str"];
        };
        for( $i =0; $i < 10; $i++ ){
            $offset = $i * 100;
            $users_id = DB::table('temp')->select('id_str')->offset( $offset )->limit(100)->get();
            $twitter_api2 = \Twitter::get("users/lookup", [
                'user_id' => $user_id,
            ]);
        }
        //jsonへ
        $json_arr = json_encode($twitter_api2, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        return $json_arr;
    }

}
