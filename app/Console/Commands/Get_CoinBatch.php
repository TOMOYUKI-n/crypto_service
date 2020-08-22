<?php

namespace App\Console\Commands;

use App\User;
use App\Temp;
use App\Tweet;
use App\Follows;
use App\Intercoin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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

    }
}
