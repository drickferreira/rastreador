<?php namespace Modules\Positions\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Modules\Vehicles\Entities\Vehicle;
use Modules\Positions\Entities\Position;
use Illuminate\Http\Request;
use Auth;

class PositionsController extends Controller {
	
	public function index()
	{
		$positions = array();
		$vehicles = Vehicle::whereHas('Device', function ($query) {
			$query->where('company_id', Auth::user()->company_id)
				->where('remove_date', null);
		})->get();
		foreach($vehicles as $vehicle){
			$position = $vehicle->Positions()
						->orderBy('memory_index', 'desc')
						->first();
			if ($position){
					$latlng = $position->latitude.','.$position->longitude;
					$positions[] = (object) array(
					'id' => $position->id,
					'vehicle_id' => $vehicle->id,
					'name' =>	$vehicle->plate,
					'latitude' => $position->latitude,
					'longitude' => $position->longitude,
					'date' => $position->date,
					'ignition' => $position->ignition,
					'speed' => $position->speed,
				);
			}
		}
		return view('positions::index', array('positions' => $positions));
	}

	public function showMap($id)
	{
		$position = Position::find($id);
		$locations = $this->getLocations($position);
		return view('positions::show', array('loc' => $locations));
	} 

	public function showAllMap(Request $request)
	{
		$locations = array();
    $ids = $request->input('ids');
        
		foreach ($ids as $id) {
			$device = Device::find($id);
			$position = $device->Positions()
						->orderBy('memory_index', 'desc')
						->first();
			if ($position){
				$locations[] = $this->getLocations($position);
			}
    }
    return view('positions::showall', array('locations' => $locations));		
	}

	function getLocations(Position $position)
	{
		$locations = array
		(
			'lat' => $position->latitude,
			'lon' => $position->longitude,
			'title' => $position->Device->name,
			'html' => '<div><input type="hidden" id="device" value="'.$position->Device->id.'"/>'.
					  '<h5>'.$position->Device->name.'</h5>'.
					  '<p>Data: '.$position->date.'</p>'.
					  '<p>Velocidade: '.$position->speed.' km/h</p></div>',
		);	
		return $locations;
	}

	public function getAddress(Request $request)
	{
		$position = Position::find($request->id);
		$latlng = $position->latitude.','.$position->longitude;
		$response = \GoogleMaps::load('geocoding')
				        ->setParamByKey('latlng', $latlng)
				        ->getResponseByKey('results.formatted_address');
	    return array_get($response, 'results.0.formatted_address');
	}

	public function updatePositions(Request $request){
		
		$data = $request->all();
		$ids = json_decode($data["ids"]);
		$new_positions = array();
		foreach ($ids as $id) {
			$device = Device::find($id);
			$position = $device->Positions()
						->orderBy('memory_index', 'desc')
						->first();
			if ($position){
				$new_positions[] = $this->getLocations($position);
			}
		}
		return json_encode($new_positions);
	}

	public function showInfo($id)
	{
		$position = Position::find($id);
		return view('positions::info', array('position' => $position));
	}
	
	public function showLast($id)
	{
		$vehicle = Vehicle::find($id);
		$positions = $vehicle->Positions()
			->orderBy('memory_index', 'desc');
		return view('positions::last', array('positions' => $positions, 'vehicle' => $vehicle));
	}

	public function showRoute(Request $request)
	{
		$locations = array();
    $ids = $request->input('ids');
		foreach ($ids as $id) {
			$position = Position::find($id);
			$locations[] = $this->getLocations($position);
    }
    return view('positions::route', array('locations' => $locations));		
	}
}