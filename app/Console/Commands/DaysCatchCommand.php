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
use GuzzleHttp\Client;
class DaysCatchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:DaysCatchCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '日時ごと、一番最初に実行';

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
        //
        //daysテーブルに取得時刻を登録
        Tweet::DaysInsert();
        var_dump('== end ==');
    }
}
