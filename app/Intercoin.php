<?php

namespace App;

use App\Tweet;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
        try {
            if ($q === "1hour") {
                //==現在からの1時間前のy-m-d hの値が出る=======デフォルトで表示
                $dt = Carbon::now();
                $dt_new = new Carbon(strval($dt));
                $dt_new->subHour();
                $date = substr($dt_new, 0, 13);
                $date = "2020-07-28 02";

            } elseif ($q === "1day") {
                //==現在からの1日前のy-m-d hの値が出る=======
                $dt = Carbon::now();
                $dt_new = new Carbon(strval($dt));
                $dt_new->subDay();
                $date = substr($dt_new, 0, 10);
                $date = "2020-07-24 22";

            } elseif ($q === "1week") {
                //==現在からの1週間前のy-m-d hの値が出る=======
                $dt = Carbon::now();
                $dt_new = new Carbon(strval($dt));
                $dt_new->subWeek();
                $date = substr($dt_new, 0, 10);
                $date = "2020-07-24 14";
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::Debug("== TransformDate error ==");
        }
        return $date;
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
                // if (!empty($hours_data[0])) {
                //     // BTCなら「BTC」を格納
                //     $Intercoin['tweet'] = $hours_data[0]->$names;
                // } else {
                //     // それ以外は「0」を格納
                //     $Intercoin['tweet'] = null;
                // }
                $Intercoin['tweet'] = $hours_data[0]->$names;

                $Intercoin['high'] = $coins_data[$i]->high;
                $Intercoin['low'] = $coins_data[$i]->low;
                $Intercoin['days_id'] = $coins_data[$i]->days_id;
                $Intercoin->save();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::Debug("error: 過去一時間分のツイート数の集計に失敗しました :: HourRanking");
            $hourRankingStatus = "Error";
            return $hourRankingStatus;
        }
        $hourRankingStatus = "OK";
        return $hourRankingStatus;
    }

    // ======================================================================
    // 過去１日分のツイート数を集計、コイン情報と組み合わせてランキング情報として出力
    // ======================================================================
    public static function DayRanking($q_time)
    {
        //初期化
        DB::table('Intercoin')->truncate();
        //$q = "1day";
        //$q_time = Intercoin::TransformDate($q);

        // coinの情報を抽出 ===
        $coins_data = DB::table('coins as c')
            ->select(DB::raw(" t.get_dates, c.coin_name, c.high, c.low, c.days_id "))
            ->join('times as t', 't.days_id', '=', 'c.days_id')
            ->where('get_dates', 'like', $q_time . '%')
            ->get();

        // 過去一日分のツイート情報を抽出 ===
        $date = $q_time;
        $coinsname = Tweet::CoinNameArrayGenerate();
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
                $Intercoin['tweet'] = null;
            }
            $Intercoin['high'] = $coins_data[$i]->high;
            $Intercoin['low'] = $coins_data[$i]->low;
            $Intercoin['days_id'] = $coins_data[$i]->days_id;
            $Intercoin->save();
        }
        //var_dump('== 処理終了 ==========');
    }

    // ======================================================================
    // 過去一週間分のツイート数を集計、コイン情報（最新）と組み合わせてランキング情報として出力
    // ======================================================================
    public static function WeekRanking($q_time)
    {

    }
}
