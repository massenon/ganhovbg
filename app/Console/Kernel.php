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

        Commands\EmailtoTicket::class,
        Commands\AutoCloseTicket::class,
        Commands\AutoOverdueTicket::class,
        Commands\AutoResponseTicket::class,
        Commands\AutoNotificationdeletes::class,
        Commands\CustomerAutoDelete::class,
        Commands\TrashedTicketsAutoDelete::class,
        Commands\UpdateData::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        $schedule->command('imap:emailticket')->everyMinute();
        $schedule->command('ticket:autoclose')->everyMinute();
        $schedule->command('ticket:autooverdue')->everyMinute();
        $schedule->command('ticket:autoresponseticket')->everyMinute();
        $schedule->command('notification:autodelete')->everyMinute();
        $schedule->command('trashedticket:autodelete')->everyMinute();
        $schedule->command('disposable:update')->weekly();
        $schedule->command('customer:inactive_delete')->everyMinute();
        $schedule->command('cache:clear')->everyThirtyMinutes();
        $schedule->command('config:clear')->everyThirtyMinutes();
        $schedule->command('route:clear')->everyThirtyMinutes();
        $schedule->command('optimize:clear')->everyThirtyMinutes();
        $schedule->command('view:clear')->everyThirtyMinutes();

        $schedule->command('Dataseed:updating')->everyMinute();

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
