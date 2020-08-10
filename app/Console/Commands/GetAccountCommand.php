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
        /**
         * 関連アカウント収集->DB保存
         * 存在しないアカウントはstatusデータがないため、エラーで弾く
         * 15分180回制限あり 50ページ*20件=1000件がMAX
         * 1日1回更新
         */
        Log::Debug('関連アカウント収集処理 == 開始');
        DB::table('account')->truncate();
        DB::table('temp')->truncate();
        $word = '仮想通貨';

        for($p =0; $p < 50; $p++){
            $twitter_api = \Twitter::get("users/search", [
                'q' => $word,
                'count' => 20,
                'page' => $p,
            ]);
            //DBへ保存
            for ($i = 0; $i < count($twitter_api); $i++) {
                $account = new Account;
                try{
                    $ex_create_at = date('Y-m-d H:i:s', strtotime((string) $twitter_api[$i]->created_at));
                    $resStatus = (array)$twitter_api[$i]->status;

                    $account['id_str'] = $twitter_api[$i]->id_str;
                    $account['name'] = $twitter_api[$i]->name;
                    $account['screen_name'] = $twitter_api[$i]->screen_name;
                    $account['description'] = $twitter_api[$i]->description;
                    $account['friends_count'] = $twitter_api[$i]->friends_count;
                    $account['followers_count'] = $twitter_api[$i]->followers_count;
                    $account['account_created_at'] = $ex_create_at;
                    $account['following'] = $twitter_api[$i]->following;
                    $account['text'] = $resStatus['text'];
                    $account->save();
                    
                }
                catch(\Exception $e){
                    Log::error($e->getMessage());
                    Log::Debug("== account wirte error ==");
                    Log::Debug($p.'page目の'.$i.'回目データ');
                }
            }
            Log::Debug('AccountDB保存　==完了==');
        }
        Log::Debug('TempDB保存 ==開始==');

        //一意データのみ、別テーブルにうつす
        $account_scorp = Account::select("id_str","name","screen_name","description","friends_count","followers_count","text")->distinct()->where('name' , 'like' , '%'.$word.'%')
                            ->orWhere('description' , 'like' , '%'.$word.'%')->get();
        for( $n =0; $n < count($account_scorp); $n++ )
        {

            try{
                    $temp = new Temp;
                    $temp['id_str'] = $account_scorp[$n]["id_str"];
                    $temp['name'] = $account_scorp[$n]["name"];
                    $temp['screen_name'] = $account_scorp[$n]["screen_name"];
                    $temp['description'] = $account_scorp[$n]["description"];
                    $temp['friends_count'] = $account_scorp[$n]["friends_count"];
                    $temp['followers_count'] = $account_scorp[$n]["followers_count"];
                    $temp['account_created_at'] = $account_scorp[$n]["account_created_at"];
                    $temp['following'] = $account_scorp[$n]["following"];
                    $temp['text'] = $account_scorp[$n]["text"];
                    $temp->save();
            }
            catch(\Exception $e){
                Log::error($e->getMessage());
                Log::Debug("== temp write error ==");
                Log::Debug($n.'レコード目のデータ');
            }
        };
        Log::Debug('関連アカウント収集処理 == DB登録正常終了');
    }
}
