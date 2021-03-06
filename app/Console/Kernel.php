<?php

namespace App\Console;

use App\Console\Commands\SendEmailApproveThreeDays;
use App\Console\Commands\SendEmailThreeDays;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Psy\Command\Command;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
       SendEmailThreeDays::class,
        SendEmailApproveThreeDays::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('email:cron')
            ->daily();

        $schedule->command('approve:cron')
            ->daily();
//        $schedule->approve('email:cron')
//            ->daily();
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
