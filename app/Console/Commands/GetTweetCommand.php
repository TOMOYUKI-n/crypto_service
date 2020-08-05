<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\ServiceProvider;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Auth;
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

class GetTweetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:gettweet';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '最後に実行。15分単位で、取得したtweetIDをtweetDBに保存';

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

        //銘柄を配列化 　出力 [0] => Array([name] => BTC)
        $coinsname = Tweet::CoinNameArrayGenerate();

        // ツイートをtweetDBに保存する処理 =======================================
        // 1 MinutesDBにレコードを作成 
        $last_insert_id = Tweet::FirstMinutesDB();
            // 銘柄分繰り返す
            for($c = 0; $c < count($coinsname); $c++ ){
                
                // DB初期化
                Tweet::Dbinit();

                // 任意のワードを設定する。リツイートを除くため -RTの文字列を加えてツイートを検索
                $word_array = $coinsname[$c]['name'];
                $word = "-RT ".$word_array." 仮想通貨";
                var_dump('Log:'.$word);

                // 15分180回の制限より、　16銘柄*10回=160回　の情報取得を実行
                for($i = 0; $i < 10; $i++){
                    // DBにデータがあるか確認
                    $query = DB::table('tweet')->get();
        
                    if (is_object($query)) {
                        $max = $query->min('tweet_id');
                        // tweet_idの最小値から-1してDBのデータと被らないようにする
                        $max_id = $max -1;
                    } else {
                        $max_id = null;
                    }
        
                    // 2 TwitterAPIを呼び出してデータを取得する
                    $twitter_api = Tweet::getTweetCountApi($word, $max_id);

                    //ステータス情報の取得
                    $statuses = $twitter_api->statuses;
                    //TwitterAPIからデータが返ってきているか確認
                    if (is_object($twitter_api)) {
                        //念の為ツイートデータが入ってるか確認
                        if (isset($twitter_api->statuses)) {
                            // 3 ツイート保存する処理
                            Tweet::TweetCountSave($twitter_api);

                        }
                    }
                }
                // 4 munitesDBに銘柄分のデータを保存 =======================================
                Tweet::TweetDBtoMinutesDB($word_array, $last_insert_id);
            }   
        //tweetテーブルのリセット
        Tweet::Dbinit();


    }
}
