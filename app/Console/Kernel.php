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
        Commands\Inspire::class,
        Commands\LoadGatewayPositions::class,
				Commands\GetDashBoardData::class,
				Commands\DeleteOldXML::class,
				Commands\getCommandResponses::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('inspire')->hourly();
        $schedule->command('positions:load')
					->everyMinute()
					->appendOutputTo('scheduler.log');
				$schedule->command('dashboard:update')
					->hourly();	
				$schedule->command('xml:delete')
					->daily();
        $schedule->command('commands:response')
					->everyMinute();
    }
}
