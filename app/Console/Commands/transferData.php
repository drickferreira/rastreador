<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webpatser\Uuid\Uuid;
use Carbon\Carbon;
use DB;

class transferData extends Command 
{

    protected $signature = 'positions:move';

    protected $description = 'Move posições do banco de Dados antigo para o novo';

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
				$positions_old = DB::connection('origem')
										 ->table('positions')
										 ->whereRaw("copied is null")
										 ->whereRaw("date < '2017-04-20 00:00:00'")
										 ->orderBy("date", "desc")
										 ->limit(1000)
										 ->get();
				//dd($positions_old);
				foreach ($positions_old as $old){
					$this->info("Id: ". $old->id. " - Data: ". $old->date." - Serial: " . $old->serial);
					$pos = DB::connection('destino')
								 ->table('positions')
								 ->where('id', $old->id)
								 ->get();
					if (count($pos)==0) {
						$position = array();
						$position['id'] = $old->id;
						$position['model'] = $old->model;
						$position['serial'] = $old->serial;
						$position['date'] = $old->date;
						$position['latitude'] = $old->latitude;
						$position['longitude'] = $old->longitude;
						$position['speed'] = $old->speed;
						$position['ignition'] = $old->ignition;
						$position['device_id'] = $old->device_id;
						$position['vehicle_id'] = $old->vehicle_id;
						$insert1 = false;
						$insert1 = DB::connection('destino')
								->table('positions')
								->insert($position);
						$information = array();
						$information['id'] = Uuid::generate(4);
						$information['transmission_reason'] = $old->transmission_reason;
						$information['hodometer'] = $old->hodometer;
						$information['power_supply'] = $old->power_supply;
						$information['temperature'] = $old->temperature;
						$information['panic'] = $old->panic;
						$information['battery_charging'] = $old->battery_charging;
						$information['battery_failure'] = $old->battery_failure;
						$information['gps_signal'] = $old->gps_signal;
						$information['gps_antenna_failure'] = $old->gps_antenna_failure;
						$information['position_id'] = $old->id;
						$information['lifetime'] = $old->lifetime;
						$insert2 = false;
						$insert2 = DB::connection('destino')
								->table('informations')
								->insert($information);
						if ($insert1 && $insert2) $insert = true;
					} else {
						$insert = true;
					}
					if($insert){
						$this->info("Salvo com sucesso!");
						DB::connection('origem')
								->table('positions')
								->where('id', $old->id)
								->update(['copied' => true]);
					} else {
						$this->error("Ocorreu um erro!");
					}
				}
    }
}
