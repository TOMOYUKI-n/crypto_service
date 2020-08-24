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
use App\Temp;
use App\Week;
use App\Day;
use App\Hour;
use App\Minute;
use App\Auto;
use App\Follows;
use Carbon\Carbon;

class AutoFollowCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:autoFollow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'autoDBに存在するフラグ1のユーザーに対して自動フォローを実行します';

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
        Log::debug("AutoFollowCommamdバッチ処理を実行します");
        // autoフラグが1のユーザーを取得..
        $userList = Auto::autoUserList();
        if(empty($userList)){
            Log::Debug("自動フォロー登録者がいません.処理を終了します.");
            return;
        }

        // ユーザー分くりかえす
        for( $n = 0; $n < count($userList); $n++)
        {
            Log::debug("===userList　繰り返す===");
            try{
                // ログインユーザーにfollowしているアカウントがあるか確認
                $followsNum = Follows::select("userId")->where("userId", "=", $userList[$n])->count();

                // tempのトータルレコード数
                $tempCount = DB::table('temp')->count();

                if($followsNum !== 0){
                    // フォローしているアカウントリスト
                    $Follows = Follows::select("accountId")->where("userId", "=", $userList[$n])->get();
                    // 配列形式にする
                    for($i = 0; $i < $followsNum; $i++)
                    {
                        $array[$i] = $Follows[$i]["accountId"];
                    }

                    // フォロー済みIDを除外するために、ループ数を調整
                    $loopNum = $tempCount - 1;
                    log::Debug("ループする数=================");
                    log::Debug($loopNum);
    



                    // フォローしていないアカウントのみ抽出する
                    for($i=0; $i < $loopNum; $i++){
                        $TargetAccounts[$i]["id_str"] = Temp::select("id_str")->whereNotIn("id_str", [ $array ] )->first();
                    }
                    Log::Debug("自動フォローする総数====");
                    Log::Debug(count($TargetAccounts));

                    // フォローする
                        Log::Debug("フォローありの場合の実行");
                        Log::Debug($userList[$n]);
                        $followRes = Tweet::autoFollowBatch($userList[$n],$TargetAccounts);
                        Log::Debug("フォローの結果==followRes==");
                        Log::Debug($followRes);
                }else{
                    // 0件フォロー= tempの全員を抽出
                    $TargetAccounts = Temp::select("id_str")->get();
                    Log::Debug("0件フォローの場合");
                    Log::Debug(count($TargetAccounts));
                        // フォローする
                        Log::Debug($userList[$n]);
                        $followRes = Tweet::autoFollowBatch($userList[$n],$TargetAccounts);
                        Log::Debug("フォローの結果==followRes==");
                        Log::Debug($followRes);
                }
            }catch(\Exception $e){
                Log::Debug($e->getMessage());
                Log::Debug("=== generateAccountList Error ===");
            }
        }
        
    }
}
