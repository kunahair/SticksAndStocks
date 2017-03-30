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
        Commands\getCompany::class,
        Commands\getCompanies::class,
        Commands\updateAllHistory::class,
        Commands\updateTopLists::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('company:updateTopLists')->dailyAt('9:55')->timezone('Australia/Melbourne');
        $schedule->command('company:getAll')->dailyAt('10:00')->timezone('Australia/Melbourne');
        $schedule->command('company:updateAllHistory')->everyMinute()->timezone('Australia/Melbourne')->between('10:05','16:30');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
