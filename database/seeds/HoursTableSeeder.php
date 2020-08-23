<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HoursTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // テスト実行時から1週間分
        for($i = 168; $i > -1; $i--){
            $now = Carbon::now();   
            // 時間分減らす
            $now->subhours($i);
            // timesNowの日時に合致する days_idを取得する
            $date = substr($now, 0, 13);
            $id = DB::table('times')->select('id')->where('get_dates', 'like', $date.'%')->first();

            DB::table('hours')->insert([
                [
                    'times_id' => $id->id,
                    'BTC'  => strval(rand(1, 990)),
                    'ETH'  => strval(rand(1, 990)),
                    'ETC'  => strval(rand(1, 990)),
                    'LSK'  => strval(rand(1, 990)),
                    'FCT'  => strval(rand(1, 990)),
                    'XRP'  => strval(rand(1, 990)),
                    'XEM'  => strval(rand(1, 990)),
                    'LTC'  => strval(rand(1, 990)),
                    'BCH'  => strval(rand(1, 990)),
                    'MONA' => strval(rand(1, 990)),
                    'XLM'  => strval(rand(1, 990)),
                    'QTUM' => strval(rand(1, 990)),
                    'DASH' => strval(rand(1, 990)),
                    'ZEC'  => strval(rand(1, 990)),
                    'XMR'  => strval(rand(1, 990)),
                    'REP'  => strval(rand(1, 990)),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }
    }
}
