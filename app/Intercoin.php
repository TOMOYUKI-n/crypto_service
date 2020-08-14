<?php

namespace App;

use App\Tweet;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Intercoin extends Model
{
    //
    protected $table = 'Intercoin';
    protected $fillable = [
        'get_dates', 'coin_name', 'tweet', 'high', 'low',
    ];

    // ======================================================================
    // リクエストにきたデータから、現在から１時間前、１日前、１週間前の日時を求める
    // ======================================================================
    public static function TransformDate($q)
    {
        Log::Debug("TransformDate 実行");
        Log::Debug($q);

        try {
            if ($q === "1hour") {
                //==現在からの1時間前のy-m-d hの値が出る=======デフォルトで表示
                $dt = Carbon::now();
                $dt_new = new Carbon(strval($dt));
                $dt_new->subHour();
                $date = substr($dt_new, 0, 13);
                //$date = "2020-07-28 02";
                Log::Debug($date);
                Log::Debug("TransformDate 1hour 完了");
                return $date;

            } else if ($q === "1day") {
                //==現在からの1日前のy-m-d hの値が出る=======
                $dt = Carbon::now();
                $dt_new = new Carbon(strval($dt));
                $dt_new->subDay();
                $date = substr($dt_new, 0, 10);
                //$date = "2020-07-25";
                Log::Debug($date);
                Log::Debug("TransformDate 1day 完了");
                return $date;
            } else {
                //==現在からの1週間前のy-m-d hの値が出る=======
                $dt = Carbon::now();
                $dt_new = new Carbon(strval($dt));
                $dt_new->subWeek();
                $date = substr($dt_new, 0, 10);
                //$date = "2020-08-13";
                Log::Debug($date);
                Log::Debug("TransformDate 1week 完了");
                return $date;
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::Debug("== TransformDate error ==");
        }
    }

    // ======================================================================
    // 過去一時間分のツイート数を集計、コイン情報と組み合わせてランキング情報として出力
    // ======================================================================
    public static function HourRanking($q_time)
    {
        //初期化
        DB::table('Intercoin')->truncate();
        // coinの情報を抽出 ===
        $coins_data = DB::table('coins as c')
            ->select(DB::raw(" t.get_dates, c.coin_name, c.high, c.low, c.days_id "))
            ->join('times as t', 't.days_id', '=', 'c.days_id')
            ->where('get_dates', 'like', $q_time . '%')
            ->get();
        // 過去一時間分のツイート情報を抽出 ===
        $hours_data = DB::table('hours as h')
            ->join('times as t', 't.id', '=', 'h.times_id')
            ->where('get_dates', 'like', $q_time . '%')
            ->get();
        // コイン銘柄を配列化、銘柄分繰り返す
        $coinsname = Tweet::CoinNameArrayGenerate();

        try {
            for ($i = 0; $i < count($coinsname); $i++) {
                $names = $coinsname[$i]['name'];
                // 一時テーブルに格納
                $Intercoin = new Intercoin;
                $Intercoin['get_dates'] = $coins_data[$i]->get_dates;
                $Intercoin['coin_name'] = $names;
                $Intercoin['tweet'] = $hours_data[0]->$names;

                $Intercoin['high'] = $coins_data[$i]->high;
                $Intercoin['low'] = $coins_data[$i]->low;
                $Intercoin['days_id'] = $coins_data[$i]->days_id;
                $Intercoin->save();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::Debug("error: 過去一時間分のツイート数の集計に失敗しました :: HourRanking");
            $RankingStatus = "Error";
            return $RankingStatus;
        }
        $RankingStatus = "OK";
        return $RankingStatus;
    }

    // ======================================================================
    // 過去１日分のツイート数を集計、コイン情報と組み合わせてランキング情報として出力
    // ======================================================================
    public static function DayRanking($q_time)
    {
        //初期化
        DB::table('Intercoin')->truncate();

        // coinの情報を抽出 ===
        $coins_data = DB::table('coins as c')
            ->select(DB::raw(" t.get_dates, c.coin_name, c.high, c.low, c.days_id "))
            ->join('times as t', 't.days_id', '=', 'c.days_id')
            ->where('get_dates', 'like', $q_time . '%')
            ->get();

        Log::Debug($coins_data);
        // 過去一日分のツイート情報を抽出 ===
        try {
            $date = $q_time;
            $coinsname = Tweet::CoinNameArrayGenerate();
            Log::Debug("coinsname========");
            //Log::Debug($coinsname);
            for ($i = 0; $i < count($coinsname); $i++) {
                $names = $coinsname[$i]['name'];
                // hoursDBの24時間分のレコードを集計
                $hours_data = Tweet::SumRecord_HoursToDays($date, $names);

                $names = $coinsname[$i]['name'];
                $Intercoin = new Intercoin;
                $Intercoin['get_dates'] = $coins_data[$i]->get_dates;
                $Intercoin['coin_name'] = $names;
                if (!empty($hours_data[0])) {
                    $Intercoin['tweet'] = $hours_data[0]->$names;
                } else {
                    $Intercoin['tweet'] = "null";
                }
                $Intercoin['high'] = $coins_data[$i]->high;
                $Intercoin['low'] = $coins_data[$i]->low;
                $Intercoin['days_id'] = $coins_data[$i]->days_id;
                $Intercoin->save();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::Debug("error: 過去一日分のツイート数の集計に失敗しました :: DaysRanking");
            $RankingStatus = "Error";
            return $RankingStatus;
        }
        $RankingStatus = "OK";
        return $RankingStatus;
    }

    // ======================================================================
    // 過去一週間分のツイート数を集計、コイン情報（最新）と組み合わせてランキング情報として出力
    // ======================================================================
    public static function WeekRanking($q_time)
    {
        // 実行した日のdays_idを取得、そのidに一致するtimesDBのget_dates
        Log::Debug("=== 過去１週間分のツイート数の集計 ===");
        Log::Debug($q_time);
        $dateTimeDb = DB::table('days')
            ->select("id", "date")
            ->where('date', '=', $q_time)
            ->get();
        Log::Debug($dateTimeDb);

        // 初期定義
        $doneId = $dateTimeDb[0]->id;
        $minusId = $doneId - 6;

        // coinの抽出 days_id 一週間分のレート情報
        Log::Debug("=== レート取得 ===");
        $coins_data = DB::table('coins')
            ->select(DB::raw('coin_name, max(high) as high, min(low) as low'))
            ->where('coin_name', '=', 'BTC')
            ->whereBetween('days_id', [$minusId, $doneId])
            ->groupBy('coin_name')
            ->get();

        Log::Debug("=== try実行 ===");
        try {
            $coinsname = Tweet::CoinNameArrayGenerate();

            Log::Debug("=== レコード集計 ===");
            $weeks_data = Tweet::SumRecord_weeks($doneId, $minusId);

            for ($i = 0; $i < count($coinsname); $i++) {
                $names = $coinsname[$i]['name'];
                // weeksDBの1週間分のレコードを集計

                $Intercoin = new Intercoin;
                $Intercoin['get_dates'] = $dateTimeDb[0]->date;
                $Intercoin['coin_name'] = $names;
                $Intercoin['tweet'] = $weeks_data[0]->$names;
                if ($names === "BTC") {
                    $Intercoin['high'] = $coins_data[$i]->high;
                    $Intercoin['low'] = $coins_data[$i]->low;
                } else {
                    $Intercoin['high'] = "不明";
                    $Intercoin['low'] = "不明";
                }
                $Intercoin['days_id'] = $dateTimeDb[0]->id;
                $Intercoin->save();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::Debug("error: 過去１週間分のツイート数の集計に失敗しました :: weeksRanking");
            $RankingStatus = "Error";
            return $RankingStatus;
        }
        $RankingStatus = "OK";
        return $RankingStatus;

    }
}
