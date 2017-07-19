<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;

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
        Commands\LoadE3Positions::class,
        Commands\LoadCRXPositions::class,
        Commands\LoadOldPositions::class,
				Commands\GetDashBoardData::class,
				Commands\DeleteOldXML::class,
				Commands\getCommandResponses::class,
				Commands\transferData::class,
				Commands\fechamento::class,
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
				$prefix = Carbon::now()->format("Ymd");
				$schedule_config = config('app.schedulers');
				if ($schedule_config['maxtrack']){
					$schedule->command('positions:maxtrack')
						->everyMinute()
						->appendOutputTo('storage/logs/maxtrack_'.$prefix.'.log');
					$schedule->command('commands:response')
						->everyMinute();
				}
				if ($schedule_config['e3']){
					$schedule->command('positions:e3')
						->everyMinute()
						->appendOutputTo('storage/logs/e3_'.$prefix.'.log');
				}
				if ($schedule_config['crx']){
					$schedule->command('positions:CRX')
						->everyMinute()
						->appendOutputTo('storage/logs/crx_'.$prefix.'.log');
				}
				if ($schedule_config['dashboard']){
					$schedule->command('dashboard:update')
						->hourly();
				}
				if ($schedule_config['positions_move']){
					$schedule->command('positions:move')
							->everyMinute()
							->appendOutputTo('positions_move.log');
				}
				if ($schedule_config['positions_old']){
					$schedule->command('positions:old')
							->everyMinute()
							->appendOutputTo('storage/logs/maxtrack_'.$prefix.'.log');
				}
				if ($schedule_config['fechamento']){
					$schedule->command('fechamento')
							->cron('0 6 1 * *');
				}
				$schedule->command('xml:delete')
					->daily();
    }
}
