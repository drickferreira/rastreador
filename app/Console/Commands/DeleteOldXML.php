<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Storage;

class DeleteOldXML extends Command 
{


    protected $signature = 'xml:delete';

    protected $description = 'Apaga arquivos XML antigos em cache';

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
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
			$local = Storage::disk('xml');
			$files = $local->files();
			$now = Carbon::now();
			$now->subDay(5);
			foreach ($files as $file){
				$timestamp = $local->getTimestamp($file);
				if ($timestamp < $now->timestamp){
					$local->delete($file);;
				}
			}
    }
}
