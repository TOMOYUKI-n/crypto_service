<?php

namespace App;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Coin;
use App\Day;
use App\Follows;
use App\Hour;
use App\Minute;
use App\Time;
use App\Tweet;
use App\Week;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Tweet extends Model
{
    //
    protected $table = 'tweet';
    protected $fillable = [
        'tweet_id',
    ];

    /**
     * 自動フォロー処理時　呼び出し関数
     * herokuの10分バッチ使用対応
     */
    public static function autoFollowBatch($loginId, $TargetAccounts)
    {
        // ログインIDの確認
        Log::Debug("LoginId================");
        Log::Debug($loginId);

        // apiを叩くトークン情報のセット
        $token = DB::table("users")->select("twitter_oauth_token")->where("id", "=", $loginId)->first();
        $tokenSecret = DB::table("users")->select("twitter_oauth_token_secret")->where("id", $loginId)->first();

        $userToken = $token->twitter_oauth_token;
        $userTokenSecret = $tokenSecret->twitter_oauth_token_secret;
        $config = config('twitter');
        
        // 署名などの生成
        $Twitter = new TwitterOAuth($config['api_key'], $config['secret_key'], $userToken, $userTokenSecret);

        /**
         * フォローする(15分に4回まで)
         * herokuの10分バッチに合わせて5分程度で終わる様にする
         * 3.75分間隔で実行(216秒間隔 ->ペースは{4人/15分 (384人/1日)}
         * *twitterユーザー認証のlimit= 400
         */

        $limitNum = 4;
        $limitTime = 216;
        try {
            if ($TargetAccounts) {
                // リストのアカウントをフォローしていく
                for ($i = 0; $i < $limitNum; $i++) {
                    Log::debug("follows 実行");
                    // 順に実行
                    $createUserId = $TargetAccounts[$i]["id_str"]["id_str"];

                    $twitter_api_post = $Twitter->post("friendships/create", ["user_id" => $createUserId]);
                    $following = $twitter_api_post->following;
                    
                    Log::debug("follows 実行完了　フォローしました");

                    // フォローを保存
                    Follows::GeneratefollowsList($createUserId, $loginId);
                    Log::Debug("FollowDBに保存しました. 一時sleepします");
                    
                    sleep($limitTime);

                    Log::Debug("再開します");
                }
                return $res = array("auto" => "OK");
            }
        } catch (\Exception $e) {
            Log::Debug($e->getMessage());
            Log::Debug("=== autoFollowBatch Error ===");
            return $res = array("auto" => "Error");
        }
    }

    /**
     * Daysに取得時間をインサート
     */
    public static function DaysInsert()
    {
        $day = new Day;
        $d = Carbon::now();
        $day['date'] = $d->format('Y-m-d');
        $day->save();
    }

    /**
     * DB初期化
     */
    public static function Dbinit()
    {
        DB::table('tweet')->truncate();
    }

    // ==========================================================================================
    // minutes関連
    // ==========================================================================================
    /**
     * 1 MinutesDBにレコードを作成
     */
    public static function FirstMinutesDB()
    {
        // 登録するレコードidを返す
        $minutes = new Minute;
        $minutes['datetime'] = Carbon::now();
        $minutes->save();
        $last_insert_id = $minutes->id;
        return $last_insert_id;
    }

    /**
     * 2 TwitterAPIを呼び出してデータを取得する
     */
    public static function getTweetCountApi(string $word, int $max_id)
    {
        $twitter_api = \Twitter::get("search/tweets", [
            'q' => $word,
            'count' => 100,
            'max_id' => $max_id,
        ]);
        return $twitter_api;
    }

    /**
     * 3 ツイート保存する処理
     */
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

    /**
     * 4 munitesDBに銘柄分のデータを保存
     */
    public static function TweetDBtoMinutesDB(string $word_array, int $last_insert_id)
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
    /**
     * Timesに取得時間をインサート
     * @return 最後に挿入したレコードid
     */
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
    /**
     * hoursに取得時間をインサート
     */
    public static function HoursInsertTimeid(int $new_times_id)
    {
        $hours = new Hour;
        $hours['times_id'] = $new_times_id;
        $hours->save();
    }

    /**
     * 銘柄を配列化 
     * @return [0] => Array([name] => BTC)
     */
    public static function CoinNameArrayGenerate()
    {
        $coinsname_array = array("BTC", "ETH", "ETC", "LSK", "FCT", "XRP", "XEM", "LTC", "BCH", "MONA", "XLM", "QTUM", "DASH", "ZEC", "XMR", "REP");
        foreach ($coinsname_array as $key => $value) {
            $Body[$key]['name'] = $value;
        };
        return $Body;
    }

    /**
     * 更新対象の日付を特定、集計
     * @return data
     */
    public static function SumRecord_MinutesToHours($new_times_id, $names)
    { 
        // 一時間分のデータを特定するための、時刻を文字列にて作成
        $time = Time::select('get_dates')->where('id', '=', $new_times_id)->get();
        $q_time = substr(strval($time[0]['get_dates']), 0, 13);
        $named = array($names);

        $data = Minute::select(
            DB::raw("sum($named[0]) as total_tweet")
        )->where('datetime', 'like', $q_time . '%')->get();
        return $data;
    }
    // ==========================================================================================
    // weeks関連
    // ==========================================================================================
    /**
     * weeksに取得日時をインサート
     * @return id
     */
    public static function WeeksInsertId()
    {
        $dayid = Day::max('id');
        $week = new Week;
        $week['days_id'] = $dayid;
        $week->save();
        $last_insert_id = $week->id;
        return $last_insert_id;
    }

    /**
     * hoursDBの24時間分のレコードを集計
     * @return data
     */
    public static function SumRecord_HoursToDays($date, $names)
    {
        $q_time = strval($date);
        $named = array($names);

        //timesDBで日付に一致するデータを取得
        $data = DB::table('hours as h')
            ->select(DB::raw(" sum($named[0]) as $named[0] "))
            ->join('times as t', 't.id', '=', 'h.times_id') //0622 23:36 timesを追加
            ->where('get_dates', 'like', $q_time . '%')
            ->get();

        return $data;
    }

    /**
     * 1週間分のレコードを集計
     */
    public static function SumRecord_weeks($doneId, $minusId)
    {
        // 想定SQL > elect sum(BTC), sum(ETH)  from weeks where days_id between 2 and 7;
        // +----------+----------+
        // | sum(BTC) | sum(ETH) |
        // +----------+----------+
        // |    23038 |    16031 |
        // +----------+----------+
        //timesDBで日付に一致するデータを取得
        $data = DB::table('weeks')
            ->select(DB::raw(" sum(BTC) as BTC, sum(ETH) as ETH, sum(ETC) as ETC, sum(LSK) as LSK
            , sum(FCT) as FCT, sum(XRP) as XRP, sum(XEM) as XEM, sum(LTC) as LTC, sum(BCH) as BCH
            , sum(MONA) as MONA, sum(XLM) as XLM, sum(QTUM) as QTUM, sum(DASH) as DASH
            , sum(ZEC) as ZEC, sum(XMR) as XMR, sum(REP) as REP "))
            ->whereBetween('days_id', [$minusId, $doneId])
            ->get();
        return $data;
    }

    // ==========================================================================================
    // coin関連
    // ==========================================================================================

    /**
     * coinテーブルに日付だけ保存し挿入レコードの生成をおこなう
     */
    public static function CoinInsert()
    {
        $dayid = Day::max('id');
        $coin = new Coin;
        $coin['days_id'] = $dayid;
        $coin->save();
    }

}
