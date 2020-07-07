<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use App\Tweet;
use App\Coin;
use App\Time;

class ClearTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cleartable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clear Command for development of table';

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
        //
        /*
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('days')->truncate();
        DB::table('weeks')->truncate();
        DB::table('times')->truncate();
        DB::table('coins')->truncate();
        DB::table('hours')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        /**/
        
    }
}
