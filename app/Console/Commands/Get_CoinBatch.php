<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\ServiceProvider;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use App\Tweet;
use App\Account;
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

        try{
            $twitter_api = \Twitter::get("users/search", [
                'q' => 'acountD3',
                'count' => 20,
                'page' => 1,
            ]);
            var_dump($twitter_api);
        }
        catch(\Exception $e){
                Log::error($e->getMessage());
                Log::Debug("== error ==");
        }

    }
}