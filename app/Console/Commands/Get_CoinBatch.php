<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\ServiceProvider;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

use App\Account;
use App\Intercoin;
use App\Temp;
use App\Tweet;
use App\User;
use App\Auto;
use App\Follows;
use Illuminate\Http\Request;

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

        $users = Auth::user()->first();
        Log::Debug($users);

        // $loginId = "1";
        // // $loginId = $request->loginId;
        // // responce定義
        // $autoResOk = array("status" => 200);
        // $autoResErr = array("status" => "Error");

        // // ログインユーザーがautoフラグ1であるか確認する
        // $loginUser = Auto::select("autoFlg")->where("userId", "=", $loginId)->first();
        // var_dump($loginUser);

        // Log::debug("=================");
        // if($loginUser == null){ $loginUser["autoFlg"] = 0; };
        // Log::debug($loginUser);

        // if($loginUser["autoFlg"] == 0){
        //     // 0なら1に変更し,実行対象にする
        //     try {
        //         Auto::autoFlgSaved($loginId);
        //         Log::Debug($autoResOk);
        //     } catch (\Exception $e) {
        //         Log::error($e->getMessage());
        //         Log::Debug("== error ==");
        //         Log::Debug($autoResErr);
        //     }
        // }else{
        //     // 1なら0に変更し,実行対象外にする
        //     try {
        //         Auto::autoFlgDelete($loginId);
        //         Log::Debug($autoResOk);
        //     } catch (\Exception $e) {
        //         Log::error($e->getMessage());
        //         Log::Debug("== error ==");
        //         Log::Debug($autoResErr);
        //     }
        // }
    }
}
