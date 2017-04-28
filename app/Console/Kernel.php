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
        Commands\LoadMaxtrackPositions::class,
				Commands\GetDashBoardData::class,
				Commands\DeleteOldXML::class,
				Commands\getCommandResponses::class,
				Commands\transferData::class,
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
/*        $schedule->command('positions:maxtrack')
					->everyMinute()
					->appendOutputTo('scheduler.log');
				$schedule->command('dashboard:update')
					->hourly();	
				$schedule->command('xml:delete')
					->daily();
        $schedule->command('commands:response')
					->everyMinute();*/
/*        $schedule->command('positions:move')
						->everyMinute()
						->appendOutputTo('positions_move.log');
*/    }
}
