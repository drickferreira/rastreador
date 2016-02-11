<?php namespace Modules\Positions\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Modules\Devices\Entities\Device;
use Modules\Positions\Entities\Position;
use Illuminate\Http\Request;
use Gmaps;

class PositionsController extends Controller {
	
	public function index()
	{
		$positions = array();
		$devices = Device::all();
		foreach($devices as $device){
			$position = $device->Positions()
						->orderBy('date', 'desc')
						->first();
			if ($position){
				$latlng = $position->latitude.','.$position->longitude;
				$response = \GoogleMaps::load('geocoding')
					        ->setParamByKey('latlng', $latlng)
					        ->getResponseByKey('results.formatted_address');
				$positions[] = (object) array(
					'id' => $position->id,
					'device_id' => $device->id,
					'name' =>	$device->name,
					'address' => array_get($response, 'results.0.formatted_address'),
					'latitude' => $position->latitude,
					'longitude' => $position->longitude,
					'direction' => $position->direction,
					'date' => $position->date,
					'ignition' => $position->ignition,
					'speed' => $position->speed,
				);
			}
		}
		return view('positions::index', ['positions' => $positions]);
	}

	public function getAddress(Request $request)
	{
		$position = Position::find($request->id);
		//dd($position);
		$latlng = $position->latitude.','.$position->longitude;
		$response = \GoogleMaps::load('geocoding')
				        ->setParamByKey('latlng', $latlng)
				        ->getResponseByKey('results.formatted_address');
		//return $response;
	    return array_get($response, 'results.0.formatted_address');
	}

	public function showMap($id)
	{
		$position = Position::find($id);
		$locations = [$this->getLocations($position)];

	    $loc = json_encode($locations);
		$map_js = <<<EOHTML
<script type='text/javascript'>
    var maplace = new Maplace({
        locations: $loc,
        controls_on_map: false
    }).Load();
</script>
EOHTML;

        return view('positions::show', ['map_js' => $map_js]);
	}
	
	public function showAllMap(Request $request)
	{
		$locations = [];
        $ids = $request->input('ids');
        
		foreach ($ids as $id) {
			$device = Device::find($id);
			$position = $device->Positions()
						->orderBy('date', 'desc')
						->first();
			if ($position){
				$locations[] = $this->getLocations($position);
			}
	    }

	    $loc = json_encode($locations);
		$map_js = <<<EOHTML
<script type='text/javascript'>
    var maplace = new Maplace({
        locations: $loc,
        view_all_text: 'Ver Todos'
    }).Load();
</script>
EOHTML;

        return view('positions::showAll', ['map_js' => $map_js]);		
	}

	function getLocations(Position $position)
	{
		$response = \GoogleMaps::load('geocoding')
			        ->setParamByKey('latlng', $position->latitude.','.$position->longitude)
			        ->getResponseByKey('results.formatted_address');
		$locations = array
		(
			'lat' => $position->latitude,
			'lon' => $position->longitude,
			'title' => $position->Device->name,
			'html' => '<div><input type="hidden" id="device" value="'.$position->Device->id.'"/>'.
					  '<h5>'.$position->Device->name.'</h5>'.
					  '<p>Data: '.$position->date.'</p>'.
					  '<p>Local: '.array_get($response, 'results.0.formatted_address').'</p>'.
					  '<p>Velocidade: '.$position->speed.' km/h</p></div>',
			'draggable' => true
		);	
		return $locations;
	}

	public function updatePositions(Request $request){
		
		$data = $request->all();
		$ids = json_decode($data["ids"]);
		$new_positions = [];
		foreach ($ids as $id) {
			$device = Device::find($id);
			$position = $device->Positions()
						->orderBy('date', 'desc')
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
        return view('positions::info', ['position' => $position]);
	}

	public function showRoute()
	{
		$positions = Position::orderBy('date', 'asc')
					->skip(50)
					->take(10)
					->get();

        $config = array();
//        $config['center'] = $position->latitude.','.$position->longitude;
        $config['zoom'] = 'auto';
        Gmaps::initialize($config);

		$polyline = array();
       	$polyline['points'] = array();
        foreach ($positions as $position) 
        {
			$latlng = $position->latitude.','.$position->longitude;
        	$polyline['points'][] = $latlng;
	        $marker = array();
	        $marker['position'] = $latlng;
	        Gmaps::add_marker($marker);        	
        }
		Gmaps::add_polyline($polyline);

        $map = Gmaps::create_map();

        return view('positions::show', ['map' => $map]);
	}
}