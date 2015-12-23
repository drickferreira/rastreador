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
		$lat = $position->latitude;
		$lng = $position->longitude;
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
        $config['center'] = $position->latitude.','.$position->longitude;
        $config['zoom'] = '16';
//        $config['map_height'] = '500px';
        Gmaps::initialize($config);

        $marker = array();
        $marker['position'] = $position->latitude.','.$position->longitude;
        $marker['infowindow_content'] = 'Saporra Funciona!';
        Gmaps::add_marker($marker);

        $map = Gmaps::create_map();

        return view('positions::show', ['map' => $map]);
		
	}
	
	public function showInfo($id)
	{
		$position = Position::find($id);
        return view('positions::info', ['position' => $position]);
	}
}