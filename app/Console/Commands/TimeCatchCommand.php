<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Tweet;

class TimeCatchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:timesInsert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'timesテーブルに時間をinsert';

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
        Tweet::TimesInsert();
        var_dump('== end ==');
    }
}
