<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Devices\Entities\Device;
use Modules\Vehicles\Entities\Vehicle;
use Modules\Positions\Entities\Position;
use Modules\Informations\Entities\Information;
use Carbon\Carbon;
use Storage;


class LoadE3Positions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'positions:e3';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Acessa o Gateway E3 e atualiza as posicoes transmitidas';

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
				$error = Storage::disk('error');
				
        $list = $ftp->files('E3/data');
        $count = 0;
				$model = 200;
				
        //$file = $list[0];
        foreach ($list as $file) 
        {
						$filename = basename($file);
						$this->info(Carbon::now()->toDateTimeString()." - Processando arquivo $file"); 
						$err = false;
						if ($content = $ftp->read($file)){
							try {
								$xml = simplexml_load_string($content);
								foreach($xml->xpath('POSITION') as $pos){
										$device = Device::where('serial', xmlGetVal($pos,'FIRMWARE/SERIAL'))
																			->where('model', $model)
																			->first();
											$date = new Carbon(xmlGetVal($pos,'GPS/DATE','str'));
											$generated_date = new Carbon((string) $pos->attributes()->generation_date);
											if ($date > $generated_date) $date = $generated_date;
											$position = array(
													'serial' => xmlGetVal($pos,'FIRMWARE/SERIAL'),
													'model' => $model,
													'date' => $date, 
													'ignition' => xmlGetVal($pos,'HARDWARE_MONITOR/INPUTS/IGNITION','int'), 
													'latitude' => xmlGetVal($pos,'GPS/LATITUDE','float'),
													'longitude' => xmlGetVal($pos,'GPS/LONGITUDE','float'),
													'speed' => xmlGetVal($pos,'GPS/SPEED','float'),
											);
											$gps_signal = xmlGetVal($pos,'GPS/FLAG_STATE/GPS_SIGNAL','int');
											$av = xmlGetVal($pos,'FIRMWARE/AV');
											$gps = 0;
											if ($gps_signal > 0 && $av = 'A') $gps = 1;
											$info = array(
													'transmission_reason' => xmlGetVal($pos,'FIRMWARE/TRANSMISSION_REASON','int'), 
													'power_supply' => xmlGetVal($pos,'HARDWARE_MONITOR/POWER_SUPPLY','float'), 
													'gps_signal' => $gps,
											);
											$new = new Position($position);
											$new->save();
											$new_info = new Information($info);
											$new->Info()->save($new_info);
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
							} catch (\Exception $e) {
								$err = true;
								$this->info($e->getMessage());
							} finally {
								if ($err) {
									$error->put("e3/".$filename, $content);
								} else {
									$local->put("e3/".$filename, $content);
								}
								$ftp->delete($file);
							}
						}


        }
        if ($count>0){
            $this->info("$count Posicoes encontradas!");
        } else {
            $this->error("Nenhuma Posicao encontrada!");
		}
    }
}
