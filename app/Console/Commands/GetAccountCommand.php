<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\ServiceProvider;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use App\Tweet;
use App\Coin;
use App\Time;
use App\Week;
use App\Day;
use App\Hour;
use App\Minute;
use App\Account;
use App\Temp;
use Carbon\Carbon;

class GetAccountCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:GetAccountCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'twitterアカウントを取得 1日に1回更新 朝5:00';

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
        Log::Debug('関連アカウント収集処理 == 開始');
        // テーブルの初期化
        DB::table('account')->truncate();
        //DB::table('temp')->truncate();
        // 15分180回の制限より、160回分は銘柄で実行しているので、残り20回分を実行 :2000件取得
        $word = '仮想通貨';
        $words = "-RT ".$word;
        for($k = 0; $k < 20; $k++){
            // DBにデータがあるか確認
            $query = DB::table('account')->get();

            if (is_object($query)) {
                $max = $query->min('tweet_id');
                $max_id = $max -1;
            } else {
                $max_id = null;
            }
            // データ取得
            $twitter_api = \Twitter::get("search/tweets", [
                'q' => $words, 'count' => 100, 'max_id' => $max_id
            ]);

            //ステータス情報の取得
            $statuses = $twitter_api->statuses;
            //TwitterAPIからデータが返ってきているか確認
            if (is_object($twitter_api)) {
                //念の為ツイートデータが入ってるか確認
                if (isset($twitter_api->statuses)) {
                    // Accountに保存
                    for ($i=0; $i < count($statuses); $i++){
                        $accounts = new Account;
                        $ex_create_at = date('Y-m-d H:i:s', strtotime((string) $statuses[$i]->user->created_at));
                    
                        $accounts['id_str'] = $statuses[$i]->user->id_str;
                        $accounts['name'] = $statuses[$i]->user->name;
                        $accounts['screen_name'] = $statuses[$i]->user->screen_name;
                        $accounts['description'] = $statuses[$i]->user->description;
                        $accounts['friends_count'] = $statuses[$i]->user->friends_count;
                        $accounts['followers_count'] = $statuses[$i]->user->followers_count;
                        $accounts['account_created_at'] = $ex_create_at;
                        $accounts['following'] = $statuses[$i]->user->following;
                        $accounts->save();
                    }
                }
            }
        }
        //一意データのみ、別テーブルにうつす



        for( $n =0; $n < count($account_scorp); $n++ )
        {
            $temp = new Temp;
            $temp['id_str'] = $account_scorp[$n]["id_str"];
            $temp['name'] = $account_scorp[$n]["name"];
            $temp['screen_name'] = $account_scorp[$n]["screen_name"];
            $temp['description'] = $account_scorp[$n]["description"];
            $temp['friends_count'] = $account_scorp[$n]["friends_count"];
            $temp['followers_count'] = $account_scorp[$n]["followers_count"];
            $temp['account_created_at'] = $account_scorp[$n]["account_created_at"];
            $temp['following'] = $account_scorp[$n]["following"];
            $temp->save();
        };
        Log::Debug('関連アカウント収集処理 == DB登録正常終了');

        $userinfo = Account::GetUsersInfo();
    }
}
