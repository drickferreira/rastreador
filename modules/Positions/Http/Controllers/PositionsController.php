<?php namespace Modules\Positions\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Modules\Vehicles\Entities\Vehicle;
use Modules\Positions\Entities\Position;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class PositionsController extends Controller {
	
	public function index(Request $request)
	{
		$data = $request->all();
		if (Auth::user()->isAccount()) {
			if (isset($data['plate'])){
				$vehicles = Auth::user()->Vehicles()->whereRaw("plate LIKE '%".strtoupper($data['plate'])."%'")
					->whereHas('Device', function ($query) {
					$query->where('company_id', Auth::user()->company_id)
						->where('remove_date', null);
				})->get();
			} else {
				$vehicles = Auth::user()->Vehicles()->whereHas('Device', function ($query) {
					$query->where('company_id', Auth::user()->company_id)
						->where('remove_date', null);
				})->get();
			}
		} else {
			if (isset($data['plate'])){
				$vehicles = Vehicle::whereRaw("plate LIKE '%".strtoupper($data['plate'])."%'")
					->whereHas('Device', function ($query) {
					$query->where('company_id', Auth::user()->company_id)
						->where('remove_date', null);
				})->get();
			} else {
				$vehicles = Vehicle::whereHas('Device', function ($query) {
					$query->where('company_id', Auth::user()->company_id)
						->where('remove_date', null);
				})->get();
			}
		}
		$positions = array();
		foreach($vehicles as $vehicle){
			$position = $vehicle->Positions()
						->orderBy('memory_index', 'desc')
						->first();
			if ($position){
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
		usort($positions, function($a, $b) {
   		return $a->date < $b->date;
		});
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
			$vehicle = Vehicle::find($id);
			$position = $vehicle->Positions()
						->orderBy('memory_index', 'desc')
						->first();
			if ($position){
				$locations[] = $this->getLocations($position);
			}
    }
    return view('positions::showall', array('locations' => $locations));		
	}

	public function dashboardMap()
	{
		$vehicles = Vehicle::whereHas('Device', function ($query) {
			$query->where('company_id', Auth::user()->company_id)
				->where('remove_date', null);
		})->get();
		$locations = array();
		foreach ($vehicles as $vehicle) {
			$position = $vehicle->Positions()
						->orderBy('memory_index', 'desc')
						->first();
			if ($position){
				$locations[] = $this->getLocations($position);
			}
    }
    return $locations;		
	}


	function getLocations(Position $position)
	{
		if ($position->vehicle_id){
			$locations = array
			(
				'lat' => $position->latitude,
				'lon' => $position->longitude,
				'title' => $position->Vehicle->plate,
				'vehicle_id' => $position->vehicle_id,
				'position_id' => $position->id,
				'html' => '<h5>'.$position->Vehicle->plate.'</h5>'.
							'<p>Data: '.$position->date.'</p>'.
							'<p>Velocidade: '.$position->speed.' km/h</p>',
			);	
		} else {
			$locations = array
			(
				'lat' => $position->latitude,
				'lon' => $position->longitude,
				'title' => $position->Device->serial,
				'position_id' => $position->id,
				'html' => '<h5>'.$position->Device->serial.'</h5>'.
							'<p>Data: '.$position->date.'</p>'.
							'<p>Velocidade: '.$position->speed.' km/h</p>',
			);	
		}
		return $locations;
	}

	public function updatePositions(Request $request){
		
		$data = $request->all();
		$ids = json_decode($data["ids"]);
		$new_positions = array();
		foreach ($ids as $id) {
			$vehicle = Vehicle::find($id);
			$position = $vehicle->Positions()
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
	
	public function showLast($id, Request $request)
	{
		$pag = $request->input('pag');
//		dd($request);
		$new = array(
			'dataini' => $request->input('dataini'),
			'datafin' => $request->input('datafin'),
			'search' => $request->input('search'),
			'page' => $request->input('page'),
		);
		$request->replace($new);		

		$pag = $pag ? $pag : 10;	

		$vehicle = Vehicle::find($id);
		$now = new Carbon();
		$now->subDays(5);
		$positions = $vehicle->Positions();//->where('date', '>', $now->toDateTimeString());
		$filter = \DataFilter::source($positions);
		$filter->add('dataini','Data Inicial', 'datetime')->format('d/m/Y H:i:s')->scope(function ($query, $value)  {
			$test = (bool)strtotime($value);
			if ($test)
      	return $query->whereRaw("date >= ?", array($value));  
			else
				return $query;
		});
		$filter->add('datafin','Data Final', 'datetime')->format('d/m/Y H:i:s')->scope(function ($query, $value)  {
			$test = (bool)strtotime($value);
			if ($test)
	      return  $query->whereRaw("date <= ?", array($value));  
			else
				return $query;
		});
		$filter->submit('Buscar');
		$filter->reset('Limpar');
		$filter->add('pag', '', 'select')->options(array('' => 'Itens por Página', 10 => '10', 20 => '20', 30 => '30', 40 => '40', 50 => '50', 100 => '100', 200 => '200', 500 => '500', 1000 => '1000'));

		$grid = \DataGrid::source($filter);
		$grid->attributes(array("class"=>"table table-striped"));
		$grid->add('<input type="checkbox" name="ids[]" value="{{ $id }}" onclick="checkSelected()">','<input type="checkbox" name="todos" id="todos" onclick="selectTodos()">');
		$grid->add('date|strtotime|date[d/m/Y H:i:s]','Data', true);
		$grid->add('<span id="{{$id}}" class="address" geo-lat="{{ $latitude }}" geo-lng="{{ $longitude }}"></span>','Endereço');
		$grid->add('<i class="fa fa-lg fa-circle {{ $ignition == 1 ? \'on\' : \'off\' }}">','Ignição');
		$grid->add('speed','Velocidade');
		$grid->add('<a class="btn btn-success btn-xs" title="Ver no Mapa" href="/positions/showMap/{{$id}}"><i class="fa fa-lg fa-map-marker"></i></a><a class="btn btn-danger btn-xs" title="Informações" href="/positions/showInfo/{{$id}}"><i class="fa fa-lg fa-info"></i></a><button type="button" class="btn btn-info btn-xs" title="Buscar Endereço" onclick="searchAddr(\'{{$id}}\')"><i class="fa fa-lg fa-search"></i></button>','Ações', true); 
		$grid->orderBy('memory_index','desc');
		$grid->paginate($pag);

		return view('positions::last', compact('filter', 'grid', 'vehicle'));
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