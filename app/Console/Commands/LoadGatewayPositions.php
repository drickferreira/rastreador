<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Devices\Entities\Device;
use Modules\Vehicles\Entities\Vehicle;
use Modules\Positions\Entities\Position;
use Carbon\Carbon;
use Storage;


class LoadGatewayPositions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'positions:load';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Acessa o Gateway e atualiza as posicoes transmitidas';

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
        $ftp = Storage::disk('ftp');
				$local = Storage::disk('xml');
				
        $list = $ftp->files('data');
        $count = 0;
				
        //$file = $list[0];
        foreach ($list as $file) 
        {
						$filename = basename($file);
						$this->info(Carbon::now()->toDateTimeString()." - Processando arquivo $file"); 
						if ($content = $ftp->read($file)){
							$xml = simplexml_load_string($content);
							$local->put($filename, $content);
							$ftp->delete($file);
						}

            foreach($xml->xpath('POSITION') as $pos){
                $device = Device::where('serial', xmlGetVal($pos,'FIRMWARE/SERIAL'))
                                  ->where('model', xmlGetVal($pos,'FIRMWARE/PROTOCOL'))
                                  ->first();
									$now = Carbon::now();
									$date = new Carbon(xmlGetVal($pos,'GPS/DATE','str'));
									if ($now->timestamp >= $date->timestamp){
//										$this->info(xmlGetVal($pos,'FIRMWARE/SERIAL').": usando date");
										$prefix = $date->timestamp * 100000;
									} else {
//										$this->info(xmlGetVal($pos,'FIRMWARE/SERIAL').":usando now");
										$prefix = $now->timestamp * 100000;
									}
									$memory_index = xmlGetVal($pos,'FIRMWARE/MEMORY_INDEX', 'int') + $prefix;
									$position = array(
											'serial' => xmlGetVal($pos,'FIRMWARE/SERIAL'),
											'model' => xmlGetVal($pos,'FIRMWARE/PROTOCOL'),
											'memory_index' => $memory_index,
											'transmission_reason' => xmlGetVal($pos,'FIRMWARE/TRANSMISSION_REASON','int'), 
											'date' => xmlGetVal($pos,'GPS/DATE','str'), 
											'power_supply' => xmlGetVal($pos,'HARDWARE_MONITOR/POWER_SUPPLY','float'), 
											'temperature' => xmlGetVal($pos,'HARDWARE_MONITOR/TEMPERATURE','int'), 
											'ignition' => xmlGetVal($pos,'HARDWARE_MONITOR/INPUTS/IGNITION','int'), 
											'panic' => xmlGetVal($pos,'HARDWARE_MONITOR/INPUTS/PANIC','int'), 
											'battery_charging' => xmlGetVal($pos,'HARDWARE_MONITOR/FLAG_STATE/BATTERY_CHARGING','int'),
											'battery_failure' => xmlGetVal($pos,'HARDWARE_MONITOR/FLAG_STATE/BATTERY_FAILURE','int'),
											'latitude' => xmlGetVal($pos,'GPS/LATITUDE','float'),
											'longitude' => xmlGetVal($pos,'GPS/LONGITUDE','float'),
											'speed' => xmlGetVal($pos,'GPS/SPEED','float'),
											'hodometer' => xmlGetVal($pos,'GPS/HODOMETER','int'),
											'lifetime' => xmlGetVal($pos,'FIRMWARE/LIFE_TIME','int'),
											'gps_signal' => xmlGetVal($pos,'GPS/FLAG_STATE/GPS_SIGNAL','int'),
											'gps_antenna_failure' => xmlGetVal($pos,'GPS/FLAG_STATE/GPS_ANTENNA_FAILURE','int'),
									);
									$new = new Position($position);
									$new->save();
									if ($device) {
										$new->Device()->associate($device);
										$new->save();
										if ($device->has('Vehicle')){
											$vehicle = $device->Vehicle;
											$new->Vehicle()->associate($vehicle);
											$new->save();
										} 
									}
									//$this->info("Nova posição: ". $new->id);
									$count++;
            }
        }
        if ($count>0){
            $this->info("$count Posicoes encontradas!");
        } else {
            $this->error("Nenhuma Posicao encontrada!");
		}
    }
}
