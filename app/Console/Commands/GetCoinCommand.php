<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\ServiceProvider;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Tweet;
use App\Coin;
use App\Time;
use App\Week;
use App\Day;
use App\Hour;
use App\Minute;

class GetCoinCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:GetCoinCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '1日ごとに実行、Coin情報を取得';

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
        // 初期定義
        $unkwonw = "不明";
        
        $client = new Client();
        $Url_BTC = "https://coincheck.com/api/ticker";
        // GETリクエストで取得
        $responseData = $client -> request("GET", $Url_BTC);
        $responseBody = json_decode($responseData -> getBody() -> getContents(), true);
    
        $result['high'] = (string)$responseBody['high'];
        $result['low']  = (string)$responseBody['low'];
        $result_array = (array)$result;
        //var_dump($result_array);
        // 銘柄を配列化
        $coinsname = Tweet::CoinNameArrayGenerate();

        for($i = 0; $i < count($coinsname); $i++ ){  
            
            $names = $coinsname[$i]['name'];

            // days_idを取得
            $coins_days_id = Day::max('id');
            $coins = new Coin;

            if( $names == "BTC")
            {
                
                $coins['coin_name'] = $names;
                $coins['high'] = $result_array['high'];
                $coins['low'] = $result_array['low'];
                $coins['days_id'] = $coins_days_id;
                $coins->save();
                
                var_dump('== BTC ================');
            }else{
                
                $coins['coin_name'] = $names;
                $coins['high'] = $unkwonw;
                $coins['low'] = $unkwonw;
                $coins['days_id'] = $coins_days_id;
                $coins->save();

                var_dump('== else ================');
            }
        }
    }
}
