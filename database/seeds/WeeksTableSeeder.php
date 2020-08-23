<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WeeksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // テスト実行時から1週間分
        for($i = 7; $i > -1; $i--){
            $now = Carbon::now();   
            // 時間分減らす
            $now->subDays($i);
            // timesNowの日時に合致する days_idを取得する
            $date = substr($now, 0, 10);
            $id = DB::table('days')->select('id')->where('date', '=', $date)->first();

            DB::table('weeks')->insert([
                [
                    'days_id' => $id->id,
                    'BTC'  => strval(rand(7200, 23760)),
                    'ETH'  => strval(rand(7200, 23760)),
                    'ETC'  => strval(rand(7200, 23760)),
                    'LSK'  => strval(rand(7200, 23760)),
                    'FCT'  => strval(rand(7200, 23760)),
                    'XRP'  => strval(rand(7200, 23760)),
                    'XEM'  => strval(rand(7200, 23760)),
                    'LTC'  => strval(rand(7200, 23760)),
                    'BCH'  => strval(rand(7200, 23760)),
                    'MONA' => strval(rand(7200, 23760)),
                    'XLM'  => strval(rand(7200, 23760)),
                    'QTUM' => strval(rand(7200, 23760)),
                    'DASH' => strval(rand(7200, 23760)),
                    'ZEC'  => strval(rand(7200, 23760)),
                    'XMR'  => strval(rand(7200, 23760)),
                    'REP'  => strval(rand(7200, 23760)),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }
    }
}
