<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CoinTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // テスト実行時から1週間分の時間
        for($i = 7; $i > -1; $i--){
            $now = Carbon::now();   
            // 1日減らす
            $now->subDays($i);
            // timesNowの日時に合致する days_idを取得する
            $date = substr($now, 0, 10);
            $id = DB::table('days')->select('id')->where('date', '=', $date)->first();

            //コインの配列化
            $coinsname_array = array("BTC", "ETH", "ETC", "LSK", "FCT", "XRP", "XEM", "LTC", "BCH", "MONA", "XLM", "QTUM", "DASH", "ZEC", "XMR", "REP");
            foreach ($coinsname_array as $key => $value) {
                $Body[$key]['name'] = $value;
            };

            // 1日分のデータを格納
            for($j= 0; $j < count($coinsname_array); $j++){

                if($Body[$j]['name'] == 'BTC'){
                    $high = strval(rand(1239303, 1359303));
                    $low = strval(rand(1222511, 1239303));
                }else{
                    $high = '不明';
                    $low = '不明';
                };

                DB::table('coins')->insert([
                    [
                        'coin_name' => $Body[$j]['name'],
                        'high' => $high,
                        'low' => $low,
                        'created_at' => $now,
                        'updated_at' => $now,
                        'days_id' => $id->id,
                    ],
                ]);
            }
        }
    }
}
