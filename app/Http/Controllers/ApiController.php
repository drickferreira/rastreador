<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Modules\Vehicles\Entities\Vehicle;

class ApiController extends Controller
{
		public function index()
		{
			echo "Você logou";
		}
		
    public function getPosition($placa)
    {
			$vehicle = Vehicle::where('plate', $placa)->first();
			$position = $vehicle->Positions()
						->orderBy('date', 'desc')
						->first();
			$retorno = array(
				'latitude' => $position->latitude,
				'longitude' => $position->longitude,
				'speed' => $position->speed,
				'ignition' => $position->ignition,
			);
			echo json_encode($retorno);
    }

}
