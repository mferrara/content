<?php namespace App\Console;

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
        \App\Console\Commands\ScrapeGoogleTrends::class,
        \App\Console\Commands\ResetStuckUpdates::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('hivemind:scrapegoogletrends')
                 ->twiceDaily(1, 13)
                 ->withoutOverlapping();
        $schedule->command('hivemind:resetstuckupdates')
                 ->hourly()
                 ->withoutOverlapping();
    }
}
