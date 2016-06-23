<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Storage;
use Auth;
use DB;
use Carbon\Carbon;
use Modules\Devices\Entities\Device;
use Modules\Companies\Entities\Company;

class ReportsController extends Controller
{
	public function NotReporting()
	{
		$contents = unserialize(Storage::get('notreporting.dat'));
		$positions = $contents[Auth::user()->company_id];

		//dd($positions);
		$grid = \DataGrid::source($positions);
		$grid->attributes(array("class"=>"table table-striped"));
		$grid->add('name', 'Cliente', true);
		$grid->add('<a href="/vehicles?plate={{$plate}}&search=1" target="_blank">{{$plate}}</a>', 'Placa', 'plate');
		$grid->add('<a href="/devices?serial={{$serial}}&search=1" target="_blank">{{$serial}}</a>', 'Serial', 'serial');
		$grid->add('date', 'Último Registro', true)->cell(function($value) {
			if ($value) {
				return $value->format('d/m/Y H:i:s');
			} else {
				return '';
			}
		});
		$grid->add('obs', 'Observação');

		$grid->paginate(20);

		return view('reports.index', compact('grid'));

	}
	
	public function installByDay()
	{
		$install = DB::table('device_vehicle')
			->join('devices', 'device_id', '=', 'devices.id')
			->select('install_date', DB::raw('count(*) as total'))
			->groupBy('install_date')
			->orderBy('install_date', 'DESC');
			
		$filter = \DataFilter::source($install);
		$filter->add('install_date','Data de Instalação','daterange')->format('d/m/Y');
		$filter->add('company_id', '', 'select')->option('','Empresa')->options(Company::lists("name", "id")->all());
		$filter->submit('Buscar');
		$filter->reset('Limpar');
		$filter->build();

		$grid = \DataGrid::source($filter);
		$grid->attributes(array("class"=>"table table-striped table-condensed"));
		$grid->add('install_date', 'Data', true)
			->cell(function($value) {
				$date = new Carbon($value);
				return $date->format('d/m/Y');
			});
		$grid->add('total', 'Total de instalações');
		$grid->paginate(10);

		return view('reports.index', compact('filter', 'grid'));
		
		
	}
}
