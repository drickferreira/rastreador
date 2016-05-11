<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Storage;
use Auth;

class ReportsController extends Controller
{
	public function NotReporting()
	{
		$contents = unserialize(Storage::get('notreporting.dat'));
		$positions = $contents[Auth::user()->company_id];

		//dd($positions);
		$grid = \DataGrid::source($positions);
		$grid->attributes(array("class"=>"table table-striped"));
		$grid->add('name', 'Cliente');
		$grid->add('plate', 'Placa');
		$grid->add('serial', 'Serial');
		$grid->add('date', 'Último Report')->cell(function($value) {
			if ($value) {
				return $value->format('d/m/Y H:i:s');
			} else {
				return '';
			}
		});
		$grid->add('obs', 'Observação');
		$grid->orderBy('date','desc');
		$grid->paginate(20);

		return view('reports.index', compact('grid'));

	}
}
