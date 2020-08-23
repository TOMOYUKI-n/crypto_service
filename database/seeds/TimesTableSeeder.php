<?php

use Illuminate\Database\Seeder;
use App\Day;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TimesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // テスト実行時から1週間分の時間
        for($i = 168; $i > -1; $i--){
    
            $now = Carbon::now();
            $timesNow = new Carbon($now);

            // 1時間減らす
            $timesNow->subHours($i);

            // timesNowの日時に合致する days_idを取得する
            $date = substr($timesNow, 0, 10);
            $id = DB::table('days')->select('id')->where('date', '=', $date)->first();

            DB::table('times')->insert([
                [
                    'get_dates' => $timesNow,
                    'created_at' => $timesNow,
                    'updated_at' => $timesNow,
                    'days_id' => $id->id,
                ]
            ]);
        }
    }
}
