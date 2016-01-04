<?php namespace Modules\Positions\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Modules\Devices\Entities\Device;
use Modules\Positions\Entities\Position;
use Illuminate\Http\Request;
use Gmaps;

class PositionsController extends Controller {
	
	public function index(Request $request)
	{
		$device_id = $request->input('device_id');
		$device = Device::find($device_id);

		$positions = $device->Positions()
					->orderBy('date', 'desc')
					->get();

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
		//dd($position);
        $config = array();
		$latlng = $position->latitude.','.$position->longitude;
        $config['center'] = $latlng;
        $config['zoom'] = '16';
//        $config['map_height'] = '500px';
        Gmaps::initialize($config);

        $marker = array();
        $marker['position'] = $latlng;
        Gmaps::add_marker($marker);

        $map = Gmaps::create_map();

        return view('positions::show', ['map' => $map]);
		
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