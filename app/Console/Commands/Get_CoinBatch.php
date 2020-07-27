<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Temp;

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

        $tempIdstr = Temp::select('id_str')->orderBy('id_str', 'desc')->get();
        // ログインしているユーザーのアカウントが、取得したid_strをフォローしているかチェックする
        Log::debug(count($tempIdstr));
        Log::debug($tempIdstr[0]["id_str"]);
        // $account = new Account;
        // try{
        //     // フォロー結果を格納
        //     $account['following'] = $twitter_api_post->following;
        //     $account->save();
        // }
        // catch(\Exception $e){
        //     Log::error($e->getMessage());
        //     Log::Debug("== account wirte error ==");
        // }

    }
}
