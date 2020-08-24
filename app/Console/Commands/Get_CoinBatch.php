<?php

namespace App\Console\Commands;

use App\Day;
use App\Auto;
use App\User;
use App\Temp;
use App\Time;
use App\Tweet;
use App\Follows;
use App\Intercoin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Faker\Generator as Faker;


class Get_CoinBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:testbatch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '動作テスト用のバッチ';

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
        $tempCount = DB::table('temp')->count();
        log::Debug("tempのトータルレコード数=================");
        log::Debug($tempCount);

        $followsNum = Follows::select("userId")->where("userId", "=", 2)->count();
        log::Debug($followsNum);

        $loopNum = $tempCount - 1;
        log::Debug("ループする数=================");      
        log::Debug($loopNum);

        $Follows = Follows::select("accountId")->where("userId", "=", 2)->get();

        for($i = 0; $i < $followsNum; $i++)
        {
            $array[$i] = $Follows[$i]["accountId"];;
        }
        $array = array(0 => "1735344702");
        log::Debug("================");
        log::Debug($array);
        
        for($i=0; $i < $loopNum; $i++){
            $TargetAccounts[$i]["id_str"] = Temp::select("id_str")->whereNotIn("id_str", [ $array ] )->first();
        }
        Log::Debug(count($TargetAccounts));
    }
}
