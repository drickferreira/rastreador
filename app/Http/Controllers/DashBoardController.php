<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Positions\Http\Controllers\PositionsController;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Storage;

class DashBoardController extends Controller
{
    public function index()
    {
     	if (Auth::check() || Auth::viaRemember()){
				$itens = array();
				$contents = unserialize(Storage::get('dashboard.dat'));
				if (array_has($contents, Auth::user()->company_id)){
					$content = $contents[Auth::user()->company_id];
					$accounts = $content['Accounts'];
					array_push($itens, array (
							'class' => 'bg-blue',
							'icon' => 'users',
							'text' => 'Clientes',
							'link' => 'accounts',
							'lines' => array(
								0 => array (
									'title' => 'Com Veículos',
									'count' => $accounts[1].' / '.$accounts[0],
									'link' => 'accounts?hasvehicle=1&search=1',
									'color' => 'primary'
								),
								1 => array (
									'title' => 'Sem Veículos',
									'count' => $accounts[2].' / '.$accounts[0],
									'link' => 'accounts?hasvehicle=2&search=1',
									'color' => 'danger'
								),
							),
						)
					);
					$vehicles = $content['Vehicles'];
					array_push($itens, array (
							'class' => 'bg-blue',
							'icon' => 'car',
							'text' => 'Veículos',
							'link' => 'vehicles',
							'lines' => array(
								0 => array (
									'title' => 'Com Rastreador',
									'count' => $vehicles[1].' / '.$vehicles[0],
									'link' => 'vehicles?hasdevice=1&search=1',
									'color' => 'primary'
								),
								1 => array (
									'title' => 'Sem Rastreador',
									'count' => $vehicles[2].' / '.$vehicles[0],
									'link' => 'vehicles?hasdevice=2&search=1',
									'color' => 'danger'
								),
							),
						)
					);
					$devices = $content['Devices'];
					array_push($itens, array (
							'class' => 'bg-blue',
							'icon' => 'tags',
							'text' => 'Rastreadores',
							'link' => 'devices',
							'lines' => array(
								0 => array (
									'title' => 'Em uso',
									'count' => $devices[1]." / ".$devices[0],
									'link' => 'devices?hasvehicle=1&search=1',
									'color' => 'primary'
								),
								1 => array (
									'title' => 'Disponíveis',
									'count' => $devices[2]." / ".$devices[0],
									'link' => 'devices?hasvehicle=2&search=1',
									'color' => 'success'
								),
							),
						)
					);
					$positions = $content['Positions'];
					array_push($itens, array (
							'class' => 'bg-blue',
							'icon' => 'map-marker',
							'text' => 'Posições',
							'link' => 'positions',
							'lines' => array(
								0 => array (
									'title' => 'Reportando',
									'count' => $positions[1]." / ".$positions[0],
									'link' => 'positions',
									'color' => 'primary'
								),
								1 => array (
									'title' => 'Não Reportando',
									'count' => $positions[2]." / ".$positions[0],
									'link' => 'notReporting',
									'color' => 'danger'
								),
							),
						)
					);
				}
				if (Auth::user()->isAccount()||Auth::user()->isUser()) {
					return view('home');
				}
				$positioncontroller = new PositionsController();
				$locations = $positioncontroller->dashboardMap();
				return view('home', compact('itens', 'locations'));
			} else {
				return redirect('auth/login');
			}
    }


}
