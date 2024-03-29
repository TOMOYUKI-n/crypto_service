<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //Commands\ClearTable::class,
        Commands\CountHoursCommand::class,
        Commands\CountWeeksCommand::class,
        Commands\DaysCatchCommand::class,
        Commands\GetCoinCommand::class,
        Commands\GetTweetCommand::class,
        Commands\TimeCatchCommand::class,
        Commands\AutoFollowCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //日付をDaysDBへ　1日ごとに実行
        $schedule->command('DaysCatchCommand')->dailyAt('00:00')->withoutOverlapping();
        //1日ごとに実行
        $schedule->command('CountWeeksCommand')->dailyAt('00:00')->withoutOverlapping();
        //1時間ごとに実行
        $schedule->command('timesInsert')->hourly()->withoutOverlapping();

        $schedule->command('CountHoursTweet')->hourlyAt(20)->withoutOverlapping();
        //15分ごとに実行
        $schedule->command('gettweet')->everyFifteenMinutes()->withoutOverlapping();

        //1日ごとに実行
        $schedule->command('GetCoinCommand')->dailyAt('07:40')->withoutOverlapping();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
