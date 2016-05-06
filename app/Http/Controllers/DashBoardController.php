<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Modules\Accounts\Entities\Account;
use Modules\Vehicles\Entities\Vehicle;
use Modules\Devices\Entities\Device;
use Modules\Positions\Entities\Position;
use Carbon\Carbon;

class DashBoardController extends Controller
{
    public function index()
    {
     	if (Auth::check() || Auth::viaRemember()){
				$itens = array();
				$accounts = Account::where('company_id', Auth::user()->company_id)->count();
				$accounts_no_vehicle = Account::where('company_id', Auth::user()->company_id)
						->doesntHave('Vehicles')->count();
				$accounts_with_vehicle = $accounts - $accounts_no_vehicle;
				array_push($itens, array (
						'class' => 'bg-blue',
						'icon' => 'users',
						'text' => 'Clientes',
						'link' => 'accounts',
						'lines' => array(
							0 => array (
								'title' => 'Com Veículos',
								'count' => $accounts_with_vehicle.' / '.$accounts,
								'link' => 'accounts',
								'color' => 'primary'
							),
							1 => array (
								'title' => 'Sem Veículos',
								'count' => $accounts_no_vehicle.' / '.$accounts,
								'link' => 'accounts',
								'color' => 'danger'
							),
						),
					)
				);
				$vehicles = Vehicle::whereHas('Account', function ($query) {
					$query->where('company_id', Auth::user()->company_id);
				})->count();
				$vehicles_no_device = Vehicle::whereHas('Account', function ($query) {
					$query->where('company_id', Auth::user()->company_id);			
				})->doesntHave('Device', 'and', function($q){
						$q->where('remove_date', null);
				})->count();
				$vehicles_with_device = $vehicles - $vehicles_no_device;
				array_push($itens, array (
						'class' => 'bg-blue',
						'icon' => 'car',
						'text' => 'Veículos',
						'link' => 'vehicles',
						'lines' => array(
							0 => array (
								'title' => 'Com Rastreador',
								'count' => $vehicles_with_device.' / '.$vehicles,
								'link' => 'vehicles?hasdevice=1&search=1',
								'color' => 'primary'
							),
							1 => array (
								'title' => 'Sem Rastreador',
								'count' => $vehicles_no_device.' / '.$vehicles,
								'link' => 'vehicles?hasdevice=2&search=1',
								'color' => 'danger'
							),
						),
					)
				);
				$devices = Device::where('company_id', Auth::user()->company_id)->count();
				$devices_withvehicle = Device::where('company_id', Auth::user()->company_id)
					->whereHas('Vehicle', function ($q) {
					$q->where('remove_date', null);
				})->count();
				$devices_novehicle = $devices - $devices_withvehicle;
				array_push($itens, array (
						'class' => 'bg-blue',
						'icon' => 'tags',
						'text' => 'Aparelhos',
						'link' => 'devices',
						'lines' => array(
							0 => array (
								'title' => 'Em uso',
								'count' => $devices_withvehicle." / ".$devices,
								'link' => 'devices?hasvehicle=1&search=1',
								'color' => 'primary'
							),
							1 => array (
								'title' => 'Disponíveis',
								'count' => $devices_novehicle." / ".$devices,
								'link' => 'devices?hasvehicle=2&search=1',
								'color' => 'success'
							),
						),
					)
				);
				$now = new Carbon();
				$now->subDays(5);

				$positions_last_week = Position::distinct()->select('vehicle_id')
					->where('date', '>', $now->toDateTimeString())
					->whereHas('Device', function ($query) {
						$query->where('company_id', Auth::user()->company_id);
					})->count('vehicle_id');

				$no_positions = $devices_withvehicle - $positions_last_week;
				array_push($itens, array (
						'class' => 'bg-blue',
						'icon' => 'map-marker',
						'text' => 'Posições',
						'link' => 'positions',
						'lines' => array(
							0 => array (
								'title' => 'Reportando',
								'count' => $positions_last_week." / ".$devices_withvehicle,
								'link' => 'positions',
								'color' => 'primary'
							),
							1 => array (
								'title' => 'Não Reportando',
								'count' => $no_positions." / ".$devices_withvehicle,
								'link' => 'notReporting',
								'color' => 'danger'
							),
						),
					)
				);
				
				if (Auth::user()->isSuperAdmin()) {
				}
				return view('home', compact('itens'));
			} else {
				return redirect('auth/login');
			}
    }


}
