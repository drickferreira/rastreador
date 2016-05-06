<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Modules\Positions\Entities\Position;
use Modules\Vehicles\Entities\Vehicle;
use Carbon\Carbon;
use Auth;

class ReportsController extends Controller
{
	public function NotReporting()
	{
		$now = new Carbon();
		$now->subDays(5);
		$last_week = Position::distinct()->select('vehicle_id')
			->whereNotNull('vehicle_id')
			->where('date', '>', $now->toDateTimeString())
			->whereHas('Device', function ($query) {
				$query->where('company_id', Auth::user()->company_id);
			})->lists('vehicle_id')->toArray();
		$vehicles = Vehicle::whereNotIn('id', $last_week)
			->whereHas('Device', function ($query) {
				$query->where('company_id', Auth::user()->company_id);
			})->with('Account')->get();
		$positions = array();
		foreach($vehicles as $vehicle){
			$position = $vehicle->Positions()
						->orderBy('memory_index', 'desc')
						->first();
			if ($position){
				$positions[] = (object) array(
					'name' => $vehicle->Account->name,
					'serial' => $position->serial,
					'plate' => $vehicle->plate,
					'date' => $position->date,
				);
			}
		}
//		dd($positions);
		usort($positions, function($a, $b) {
   		return $a->date > $b->date;
		});
		
//		dd($vehicles);
		$grid = \DataGrid::source($positions);
		$grid->attributes(array("class"=>"table table-striped"));
		$grid->add('name', 'Cliente');
		$grid->add('plate', 'Placa');
		$grid->add('serial', 'Serial');
		$grid->add('date|strtotime|date[d/m/Y H:i:s]', 'Último Report');
		$grid->orderBy('date','desc');
		$grid->paginate(20);

		return view('reports.index', compact('grid'));

	}
}
