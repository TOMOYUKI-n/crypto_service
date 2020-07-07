<?php

namespace App\Console\Commands;

use App\Account;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
        //php artisan command:testbatch
        /**
         * 100件ずつしかapiで取得
         * 1日MAX1000件までのフォロー-> 100件*10回=1000件　よってn=10
         */
        // $account_scorp = Temp::select()->get();
        // for( $n =0; $n < count($account_scorp); $n++ )
        // {
        //     $user_id[$n] = $account_scorp[$n]["id_str"];
        // };
        // for( $i =0; $i < 10; $i++ ){
        //     $offset = $i * 100;
        //     $users_id = DB::table('temp')->select('id_str')->offset( $offset )->limit(100)->get();

        //     Log::debug( $users_id );

        //     $twitter_api2 = \Twitter::get("users/lookup", [
        //         'user_id' => $user_id,
        //     ]);
        // }
        // Log::debug( $twitter_api2 );
        // var_dump('======================= 終了 ==');
        $word = '仮想通貨';
        $account_scorp = Account::select('id_str')
            ->distinct()->where('name', 'like', '%' . $word . '%')
            ->orWhere('description', 'like', '%' . $word . '%')->get();
        Log::Debug('==account_scorp=================');
        Log::Debug($account_scorp);
        for ($n = 0; $n < count($account_scorp); $n++) {
            $user_id[$n] = $account_scorp[$n]["id_str"];
        };
        Log::Debug('==user_id=================');
        Log::Debug($user_id);
    }
}
