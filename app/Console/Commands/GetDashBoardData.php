<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Companies\Entities\Company;
use Modules\Vehicles\Entities\Vehicle;
use Modules\Positions\Entities\Position;
use Carbon\Carbon;
use Storage;

class GetDashBoardData extends Command 
{

    protected $signature = 'dashboard:update';

    protected $description = 'Atualiza os dados do DashBoard';

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
        $companies = Company::all();
				$parameter = array();
				$report_positions = array();
				foreach ($companies as $company){
					$parameter[$company->id] = array();
					
					//Accounts
					$accounts = $company->Accounts->where('active',true)->count();
					$accounts_no_vehicle = $company->Accounts()->doesntHave('Vehicles', 'and', function($q){
						$q->where('vehicles.active',true);
					})->count();
					$accounts_with_vehicle = $accounts - $accounts_no_vehicle;
					$parameter[$company->id]['Accounts'] = array($accounts, $accounts_with_vehicle, $accounts_no_vehicle);
					
					//Vehicles
					$vehicles = $company->Vehicles->where('active',true)->count();
					$vehicles_with_device = $company->Vehicles()->where('vehicles.active', true)->has('Device')->count();
					$vehicles_no_device = $vehicles - $vehicles_with_device;
					$parameter[$company->id]['Vehicles'] = array($vehicles, $vehicles_with_device, $vehicles_no_device);
					
					//Devices
					$devices = $company->Devices()->count();
					$devices_no_vehicle = $devices - $vehicles_with_device;
					$parameter[$company->id]['Devices'] = array($devices, $vehicles_with_device, $devices_no_vehicle);
					
					//Positions
					$now = new Carbon();
					$now->subDays(5);
					$vehicles = $company->Vehicles()->where('vehicles.active', true)->has('Device')->get();
					
					$positions = array();
					foreach($vehicles as $vehicle){
						$position = $vehicle->Positions()
									->orderBy('date', 'desc')
									->first();
						if ($position){
							if ($position->date < $now) {
								$positions[] = (object) array(
									'name' => $vehicle->Account->name,
									'serial' => $position->serial,
									'plate' => $vehicle->plate,
									'date' => $position->date,
									'obs' => 'Veículo não reportando há pelo menos 5 dias',
								);
							}
						} else {
							$positions[] = (object) array(
								'name' => $vehicle->Account->name,
								'serial' => $vehicle->Device->serial,
								'plate' => $vehicle->plate,
								'date' => null,
								'obs' => 'Veículo não possui registros',
							);
						}
					}
					usort($positions, function($a, $b) {
						return $a->date > $b->date;
					});
					$report_positions[$company->id] = $positions;
					$no_positions = count($positions);					
					$positions_last_week = $vehicles_with_device - $no_positions;
					$parameter[$company->id]['Positions'] = array($vehicles_with_device, $positions_last_week, $no_positions);
					
				}
				Storage::put('notreporting.dat', serialize($report_positions));
				Storage::put('dashboard.dat', serialize($parameter));

				//Veículos com posição travada
				$end = new Carbon();
				$start = new Carbon();
				$start->subHours(3);
				$positions = Position::select('devices.company_id', 'devices.serial', 'device_id', 'accounts.name', 'vehicles.plate', 'positions.vehicle_id', 'latitude', 'longitude')
															->join('devices','device_id', '=','devices.id')
															->join('vehicles','positions.vehicle_id', '=','vehicles.id')
															->join('accounts','vehicles.account_id', '=','accounts.id')
															->whereBetween('date', array($start, $end))
															->where('ignition',true)
															->groupBy('devices.company_id', 'devices.serial', 'device_id', 'accounts.name', 'vehicles.plate', 'positions.vehicle_id', 'latitude', 'longitude')
															->havingRaw('COUNT(*) > 50')
															//->toSql();
															->get();
				$grouped = $positions->groupBy('company_id');
				//dd($grouped);
				Storage::put('travados.dat', serialize($grouped->toArray()));
    }
}
