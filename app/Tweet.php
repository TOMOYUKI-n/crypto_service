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
use GuzzleHttp\Client;

class Tweet extends Model
{
    //
    protected $table = 'tweet';
    protected $fillable = [
        'tweet_id'
    ];
    // Daysに取得時間をインサート ==============================
    public static function DaysInsert()
    {
        $day = new Day;
        $d = Carbon::now();
        $day['date'] = $d->format('Y-m-d');
        $day->save();
    }
    // DB初期化 ==============================
    public static function Dbinit()
    {
        DB::table('tweet')->truncate();
    }
// ==========================================================================================
// minutes関連
// ==========================================================================================
    // 1 MinutesDBにレコードを作成 ==============================
    public static function FirstMinutesDB()
    {
        // 登録するレコードidを返す
        $minutes = new Minute;
        $minutes['datetime'] = Carbon::now();
        $minutes->save();
        $last_insert_id = $minutes->id;
        return $last_insert_id;
    }
    // 2 TwitterAPIを呼び出してデータを取得する ==============================
    public static function getTweetCountApi(string $word, int $max_id)
    {
        //TwitterAPIを呼び出してデータを取得する
            $twitter_api = \Twitter::get("search/tweets", [
                'q' => $word,
                'count' => 100,
                'max_id' => $max_id
            ]);
            return $twitter_api;
    }
    // 3 ツイート保存する処理 ==============================
    public static function TweetCountSave(object $twitter_api)
    {
        //ステータス情報の取得
        $statuses = $twitter_api->statuses;
        if (is_array($statuses)) {
            //ステータス情報が存在する場合、ステータス情報のカウント
            $status_count = count($statuses);
            
            //取得分繰り返す
            for ($i = 0; $i < $status_count; $i++) {
                $tweet = new Tweet;
                //取得したツイートの数を変数に格納、ツイート情報を保存
                $status = $statuses[$i];
                $tweet['tweet_id'] = $status->id;
                $tweet->save();
            }
        }
    }
    // 4 munitesDBに銘柄分のデータを保存 ==============================
    public static function TweetDBtoMinutesDB(string $word_array,int $last_insert_id)
    {
        // 1レコードに tweetDBの一意レコードの総数を格納、カラム名は引数に入っている銘柄
        $minutes_dis = Tweet::distinct()->select('tweet_id')->get()->count();
        $update_record = Minute::find($last_insert_id);
        $update_record[$word_array] = $minutes_dis;
        $update_record->save();

    }

// ==========================================================================================
// hours関連
// ==========================================================================================
    // Timesに取得時間をインサート ==============================
    public static function TimesInsert()
    {
        $times = new Time;
        $t = Carbon::now();
        $times['get_dates'] = $t->format('Y-m-d H:i');
        $times['days_id'] = Day::max('id');
        $times->save();
        $last_insert_id = $times->id;
        return $last_insert_id;
    }
    // hoursに取得時間をインサート ==============================
    public static function HoursInsertTimeid(int $new_times_id)
    {
        $hours = new Hour;
        $hours['times_id'] = $new_times_id;
        $hours->save();
    }

    // 銘柄を配列化 　出力 [0] => Array([name] => BTC) =========
    public static function CoinNameArrayGenerate()
    {
        $coinsname_array = array("BTC","ETH","ETC","LSK","FCT","XRP","XEM","LTC","BCH","MONA","XLM","QTUM","DASH","ZEC","XMR","REP");
        foreach ($coinsname_array as $key => $value){
            $Body[$key]['name'] = $value;       
        };
        return $Body;
    }
    // 更新対象の日付を特定、集計 ==============================
    public static function SumRecord_MinutesToHours(int $new_times_id, $names)
    {   // 一時間分のデータを特定するための、時刻を文字列にて作成
        $time = Time::select('get_dates')->where('id', '=', $new_times_id)->get();
        $q_time = substr( strval( $time[0]['get_dates'] ), 0, 13);
        $named = array($names);

        $data = Minute::select(
                    DB::raw("sum($named[0]) as total_tweet")
                    )->where('datetime', 'like', $q_time.'%')->get();
        return $data;
    }
// ==========================================================================================
// weeks関連
// ==========================================================================================
    // weeksに取得日時をインサート ==============================
    public static function WeeksInsertId()
    {
        $dayid = Day::max('id');
        $week = new Week;
        $week['days_id'] = $dayid;
        $week->save();
        $last_insert_id = $week->id;
        return $last_insert_id;
    }
    // hoursDBの24時間分のレコードを集計 ==============================
    public static function SumRecord_HoursToDays(string $date, $names)
    {
        $q_time = strval( $date );
        $named = array($names);

        //timesDBで日付に一致するデータを取得
        $data = DB::table('hours as h')
            ->select( DB::raw(" sum($named[0]) as $named[0] ") )
            ->join('times as t', 't.id', '=', 'h.times_id')//0622 23:36 timesを追加
            ->where('get_dates', 'like', $q_time.'%')
            ->get();
        
        return $data;
    }


// ==========================================================================================
// coin関連
// ==========================================================================================

    // coincheckAPIにて取引価格を取得、coinテーブルに保存 ==============================
    public static function CoinInsert()
    {
        $dayid = Day::max('id');
        $coin = new Coin;
        $coin['days_id'] = $dayid;
        $coin->save();
    }



}
