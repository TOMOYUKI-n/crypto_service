<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Tweet;
use App\Coin;
use App\Time;
use App\Week;
use App\Day;
use App\Hour;
use App\Minute;

class CountWeeksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:CountWeeksCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '1時間単位で得たtweet数合計を1日分まとめて集計し、weeksDBに保存';

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
    {
        $date = Day::max('date');
        $dayid = Day::max('id');
        $week = new Week;
        $week['days_id'] = $dayid;
        
        // コイン銘柄を配列化、銘柄分繰り返す
        $coinsname = Tweet::CoinNameArrayGenerate();
            for($i = 0; $i < count($coinsname); $i++)
            {
                $names = $coinsname[$i]['name'];
                // hoursDBの24時間分のレコードを集計
                $sumrec = Tweet::SumRecord_HoursToDays($date, $names);
                // weeksDBに格納する
                $week[$names] = $sumrec[0]->$names;
            }
            $week->save();
            var_dump('== end =='.$date);
    }
}