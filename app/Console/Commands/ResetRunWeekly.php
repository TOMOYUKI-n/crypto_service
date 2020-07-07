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

class ResetRunWeekly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ResetRunWeekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '1週間に1度、hours,weeks,days,timesの1日分のレコードを削除';

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
        //取得する日時は8日前の分
        $datetime = Carbon::now()->format('Y-m-d');//->subDay(1)
        $q_time = substr( strval( $datetime ), 0, 10);
        
        $select_day_id = Day::select("id")
                            ->where('date', 'like', $q_time.'%')
                            ->get();
        
        var_dump('== times select_day_id');
        var_dump($select_day_id);
        
        Day::where('date', 'like', $q_time.'%')->delete();
        
        //timesDBで日付に一致するデータを取得
        
        if( $select_day_id->isEmpty() )
        {
            var_dump('== else =====');
        }else{
            var_dump('== true =====');
            
            //weeks 削除
            DB::table('weeks as w')
                ->join('days as d', 'd.id', '=', 'w.days_id')
                ->where('days_id', '=', $select_day_id)
                ->delete();
            var_dump('== weeks delete');

            //times idを確保
            $delete_times_id = Time::select("id")
                            ->where('days_id', $select_day_id)
                            ->get();
            var_dump('==================');
            var_dump($delete_times_id);
            
            //hours　削除
            DB::table('hours as h')
                ->join('times as t', 't.id', '=', 'h.times_id')
                ->where('times_id', '=', $delete_times_id)
                ->delete();
            var_dump('== A ===========================');

            //times　削除
            DB::table('times as t')
                ->join('days as d', 'd.id', '=', 't.days_id')
                ->where('days_id', '=', $select_day_id)
                ->delete();
            var_dump('== times =====');
            
        }
    }
}
