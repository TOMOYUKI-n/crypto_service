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
use Illuminate\Support\ServiceProvider;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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

        $twitter_api = \Twitter::get("account/verify_credentials", [
                'Name' => '四十男🎉相互フォロー🎉',
        ]);
        // $resStatus = (array)$twitter_api->status;
        // 1318907000932114432

        var_dump($twitter_api);
    }
}
