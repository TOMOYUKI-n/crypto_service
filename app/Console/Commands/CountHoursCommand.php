<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Tweet;
use App\Coin;
use App\Time;
use App\Week;
use App\Day;
use App\Hour;
use App\Minute;
use Carbon\Carbon;

class CountHoursCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:CountHoursTweet';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '３番目に実行。15分単位で得たtweet数を１時間分まとめて集計し、HoursDBに保存';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {   //一時間に１回実行

        //必ずtimesにDB登録しておく
        $new_times_id = Time::max('id');

        //hoursに idを先に登録
        //Tweet::HoursInsertTimeid( $new_times_id );
        $hours = new Hour;
        $hours['times_id'] = $new_times_id;

        //$new_times_id = 10;
        // コイン銘柄を配列化、銘柄分繰り返す
        $coinsname = Tweet::CoinNameArrayGenerate();
            for($i = 0; $i < count($coinsname); $i++)
            {
                $names = $coinsname[$i]['name'];
                
                //レコードの集計 
                $sumrec = Tweet::SumRecord_MinutesToHours($new_times_id, $names);

                // 保存
                //$hour = Hour::find($new_times_id);
                $hours[$names] = $sumrec[0]["total_tweet"];
            }
            var_dump('=== end ==========');
            $hours->save();

    }
}
