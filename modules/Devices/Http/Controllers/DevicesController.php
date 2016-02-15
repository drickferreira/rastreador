<?php namespace Modules\Devices\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Modules\Devices\Entities\Device;
use Modules\Companies\Entities\Company;
use Modules\Vehicles\Entities\Vehicle;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class DevicesController extends Controller {
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if (Auth::user()->isSuperAdmin() || Auth::user()->isAdmin()) {
			if (Auth::user()->isSuperAdmin()) {
				$filter = \DataFilter::source(Device::with('Company'));
			} else if (Auth::user()->isAdmin()) {
				$filter = \DataFilter::source(Device::with('Company')->where('company_id', Auth::user()->company_id));
			}
			$filter->add('name','Identificação', 'text');
			$filter->add('serial','Serial', 'text');
			$filter->add('hasvehicle', 'Atribuído', 'select')
				->options(array(0 => 'Todos', 1 => 'Com veículo', 2 => 'Sem Veículo'))
				->scope('hasvehicle');
			$filter->submit('Buscar');
			$filter->reset('Limpar');
			$filter->build();
			
			$grid = \DataGrid::source($filter);
			$grid->attributes(array("class"=>"table table-striped"));
			$grid->add('name','Identificação', true);
			$grid->add('serial','Número de Série', true);
			$grid->add('{{ fieldValue("devices", $model) }}','Modelo', true);
			$grid->add('assignedvehicle','Veículo', true);
			if (Auth::user()->isSuperAdmin()) {
				$grid->add('Company.name','Empresa', true);
				$grid->edit('devices/edit', 'Ações','show|modify|delete');
				$grid->link('devices/edit',"Novo Aparelho", "TR");
			} else if (Auth::user()->isAdmin()) {
				$grid->add('<a class="btn btn-default" href="devices/vehicle/{{$id}}"><i class="fa fa-car"></i></a>', '');
			}
			return view('devices::index', compact('filter', 'grid'));
		} else {
			return view('errors.503');
		}
	}

	public function getVehicle($id)
	{
		if (Auth::user()->isAdmin()) {
			$device = Device::findOrFail($id);
			$vehicles = ['' => ''] + Vehicle::whereHas('Account', function ($query) {
				$query->where('company_id', Auth::user()->company_id);			
			})->doesntHave('Device', 'and', function($q){
    		$q->where('remove_date', null);
			})->get()->lists("fullname", "id")->all();
			$form = \DataForm::create();
			$form->add('device_id', '', 'hidden')->insertValue($device->id);
			$form->add('device_name', 'Aparelho', 'text')->insertValue($device->name)->mode('readonly');
			if ($device->assignedvehicle){
				$vehicle = $device->Vehicle->where('remove_date', null)->first();
				$form->add('assignedvehicle', 'Veículo', 'text')->insertValue($vehicle->plate)->mode('readonly');
				$form->add('vehicle_id', '', 'hidden')->insertValue($vehicle->id);
				$form->add('install_date', 'Data de Instalação', 'date')->insertValue($vehicle->pivot->install_date)->mode('readonly');
				$form->add('description', 'Observações', 'textarea')->insertValue($vehicle->pivot->description)->mode('readonly');
				$form->add('action','', 'hidden')->insertValue('remove');
				$form->submit('Remover Veículo');
			} else {
				$form->add('vehicle_id', 'Veículo', 'select')->options($vehicles);
				$form->add('install_date', 'Data de Instalação', 'date')->format('d-m-Y');
				$form->add('description', 'Observações', 'textarea');
				$form->add('action','', 'hidden')->insertValue('assign');
				$form->submit('Salvar');
			}
			return view('devices::vehicle', compact('form'));
		} else {
			return view('errors.503');
		}
	}

	public function postVehicle(Request $request)
	{
		if (Auth::user()->isAdmin()) {
			$device = Device::findOrFail($request->device_id);
			if ($request->action == 'assign') {
				$this->validate($request, ['vehicle_id' => 'required', 'install_date' => 'required', 'description' => 'required|min:15']);
				$device->Vehicle()->attach($request->vehicle_id, array(
					'install_date' => $request->install_date,
					'description' => $request->description,
				));
			} elseif ($request->action == 'remove') {
				$vehicle = $device->Vehicle->where("id", $request->vehicle_id)->first();
				$vehicle->pivot->remove_date = Carbon::now()->toDateString();
				$vehicle->pivot->save();
			}
			return redirect('devices');
		} else {
			return view('errors.503');
		}
	}

	public function edit()
	{
		if (Auth::user()->isSuperAdmin()) {
			$form = \DataEdit::source(new Device);
			$form->link("devices","Voltar", "TR")->back();
			$form->text('name','Identificação')->rule('required|min:5');
			$form->select('model','Modelo')->options(config("dropdown.devices"));
			$companies = ['' => ''] + Company::lists("name", "id")->all();
			$form->select('company_id', 'Empresa')->options($companies);
			return $form->view('devices::edit', compact('form'));
		} else {
			return view('errors.503');
		}
	}


	
}