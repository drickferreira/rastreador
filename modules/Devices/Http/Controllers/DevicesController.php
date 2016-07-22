<?php namespace Modules\Devices\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Modules\Devices\Entities\Device;
use Modules\Companies\Entities\Company;
use Modules\Vehicles\Entities\Vehicle;
use Modules\Positions\Entities\Position;
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
				$filter = \DataFilter::source(Device::with('Vehicle', 'Company'));
			} else if (Auth::user()->isAdmin()) {
				$filter = \DataFilter::source(Device::with('Vehicle')->where('company_id', Auth::user()->company_id));
			}
			$filter->add('name','Identificação', 'text');
			$filter->add('serial','Serial', 'text')->clause('where')->operator('=');
			$filter->add('model','Modelo', 'select')->option('','Modelo')->options(config("dropdown.devices"));
			$filter->add('hasvehicle', 'Atribuído', 'select')
				->options(array(0 => 'Todos', 1 => 'Com veículo', 2 => 'Sem Veículo'))
				->scope('hasvehicle');
			if (Auth::user()->isSuperAdmin()) {
				$filter->add('company_id', '', 'select')->option('','Empresa')->options(Company::lists("name", "id")->all());
			} 
			$filter->submit('Buscar');
			$filter->reset('Limpar');
			$filter->build();
			
			$grid = \DataGrid::source($filter);
			$grid->label('Aparelhos');
			$grid->attributes(array("class"=>"table table-striped"));
			$grid->add('name','Identificação', true);
			$grid->add('serial','Número de Série', true);
			$grid->add('{{ fieldValue("devices", $model) }}','Modelo', 'model');
			$grid->add('Vehicle.plate','Veículo');
			if (Auth::user()->isSuperAdmin()) {
				$grid->add('Company.name','Empresa', 'company_id');
				$grid->edit('devices/edit', 'Ações','show|modify|delete');
				$grid->link('devices/edit',"Novo Aparelho", "TR");
			} else {
				$grid->add('<a title="Visualizar" href="devices/edit?show={{$id}}"><span class="glyphicon glyphicon-eye-open"> </span></a> <a title="Modificar" href="devices/edit?modify={{$id}}"><span class="glyphicon glyphicon-edit"> </span></a> <a title="Instalar/Remover" href="devices/vehicle/{{$id}}"><span class="glyphicon glyphicon-wrench"> </span></a> <a title="Última Posição" href="devices/test/{{$id}}"><span class="glyphicon glyphicon-thumbs-up"> </span></a>', 'Ações');
			}
			$grid->link('devices/test',"Teste por Serial", "TR");
			$grid->paginate(10);
			return view('devices::index', compact('filter', 'grid'));
		} else {
			return view('errors.503');
		}
	}

	public function getVehicle($id)
	{
		if (Auth::user()->isAdmin()) {
			$device = Device::find($id);
			$vehicles = Vehicle::whereHas('Account', function ($query) {
				$query->where('company_id', Auth::user()->company_id);			
			})->doesntHave('Device')
				->get()->lists("fullname", "id")->all();
			$form = \DataForm::create();
			$form->add('device_id', '', 'hidden')->insertValue($id);
			$form->add('device_name', 'Aparelho', 'text')->insertValue($device->name)->mode('readonly');
			$form->link('/devices', 'Voltar', 'TR');
			if ($device->vehicle_id != ''){
				$form->add('vehicle_name', 'Veículo', 'text')->insertValue($device->Vehicle->fullname)->mode('readonly');
				$form->add('vehicle_id', '', 'hidden')->insertValue($device->vehicle_id);
				$form->add('install_date', 'Data de Instalação', 'date')->format('d/m/Y')->insertValue($device->install_date)->mode('readonly');
				$form->textarea('description', 'Observações')->insertValue($device->description)->mode('readonly');
				$form->add('action','', 'hidden')->insertValue('remove');
				$form->label('Remover Aparelho');
				$form->submit('Confirma Retirada');
			} else {
				$form->add('vehicle_id', 'Veículo', 'select')->option("","Selecione")->options($vehicles)->rule('required');
				$form->add('install_date', 'Data de Instalação', 'date')->format('d/m/Y')->rule('required');
				$form->textarea('description', 'Observações')->rule('required|min:15');
				$form->add('action','', 'hidden')->insertValue('assign');
				$form->label('Instalar Aparelho');
				$form->submit('Salvar');
			}
			return $form->view('devices::vehicle', compact('form'));
		} else {
			return $form->view('errors.503');
		}
	}

	public function postVehicle(Request $request)
	{
		$device = Device::findOrFail($request->device_id);
		if ($request->action == 'assign') {
			$device->Vehicle()->associate($request->vehicle_id);
			$device->install_date = $request->install_date;
			$device->description = $request->description;
			$device->save();
			return redirect('devices')->with('message','Veículo associado com sucesso!'); 
		} else {
			$device->Vehicle()->dissociate();
			$device->install_date = NULL;
			$device->description = NULL;
			$device->save();
			return redirect('devices')->with('message','Veículo removido com sucesso!'); 
		}
	}

	public function edit()
	{
		$form = \DataEdit::source(new Device);
		$form->link("devices","Voltar", "TR")->back();
		if (Auth::user()->isSuperAdmin()) {
			$form->text('name','Identificação')->rule('required|min:5');
			$form->text('serial','Serial')->rule('required|min:5')->unique();
			$form->select('model','Modelo')->options(config("dropdown.devices"));
			$form->select('company_id', 'Empresa')->option('','')->options(Company::lists("name", "id")->all());
			if ($form->status == "show"){
				$form->link("#", "Registro de Alterações", "TR", ['onClick'=>"MyWindow=window.open('audit/".$form->model->id."','MyWindow','width=800,height=400'); return false;"]);
			}
		} elseif (Auth::user()->isAdmin()) {
			$form->text('name','Identificação')->mode('readonly');
			$form->text('serial','Serial')->mode('readonly');
			$form->select('model','Modelo')->options(config("dropdown.devices"))->mode('readonly');
			if ($form->status == "show") {
				if ($form->model->vehicle_id == ''){
					$form->link("/devices/vehicle/".$form->model->id, "Atribuir Veículo", "TR", ['class'=>"btn btn-primary"]);
				} else {
					$form->autocomplete('Vehicle.fullname', 'Veículo')->search(array('plate','model'));
					$form->textarea('description', 'Observações');
					$form->link("/devices/vehicle/".$form->model->id, "Remover Veículo", "TR", ['class'=>"btn btn-danger"]);
				}
			} else if ($form->status == "modify") {
				if ($form->model->vehicle_id != ''){
					$form->textarea('description', 'Observações')->rule('required|min:15');
				} 
			}

		} else {
			return redirect()->back()->with('error', 'Você não tem permissão para acessar esse módulo!');
		}
		if ($form->status == 'create'){
			$form->label('Novo Aparelho');
		} else {
			$form->label($form->model->serial);
		}
		$form->saved(function () use ($form){
			return redirect('devices')->with('message','Registro salvo com sucesso!'); 
		});
		$form->build();
		return $form->view('devices::edit', compact('form'));
	}
	
	public function test($id)
	{
		$device = Device::findOrFail($id);
		$position = $device->Positions()
						->orderBy('memory_index', 'desc')
						->first();
		return view('devices::test', compact('position', 'device'));
	}

	public function testSerial()
	{
		return view('devices::testserial');
	}
	
	public function searchBySerial(Request $request)
	{
		$data = $request->all();
		$model = $data['model'];
		$serial = $data['serial'];
		$index = $data['index'];
		$position = Position::where('serial', '=', $serial)
												->where('memory_index', '>', $index)
												->orderBy('memory_index', 'asc')
												->get();
		return json_encode($position);
	}

	public function searchLastBySerial(Request $request)
	{
		$data = $request->all();
		$model = $data['model'];
		$serial = $data['serial'];
		$position = Position::where('serial', $serial)
												->orderBy('memory_index', 'desc')
												->first();
		return json_encode($position);
	}
	
	public function audit($id)
	{
		$device = Device::findOrFail($id);
		$logs = $device->logs;
		//dd($logs);
		$audit = array();
		$labels = array(
			'name' => 'Identificação',
			'serial' => 'Número de Série',
			'model' => 'Modelo',
			'company_id' => 'Empresa',
			'vehicle_id' => 'Veículo',
			'description' => 'Observações'
		);
		if ($logs)
		foreach($logs as $log)
		{
			foreach( $log->new_value as $key => $value)
			{
				switch ($key){
					case 'model':
						$audit[] = array(
							'label' => $labels[$key],
							'old' => testVal($log->old_value, $key) ? fieldValue("devices", $log->old_value[$key]) : '[novo]',
							'new' => fieldValue("devices", $value),
							'user' => $log->user->username,
							'date' => date('d/m/Y H:i:s', strtotime($log->updated_at)),
						);
						break;
					case 'vehicle_id':
						$audit[] = array(
							'label' => $labels[$key],
							'old' => testVal($log->old_value, $key) ? Vehicle::find($log->old_value[$key])->plate : '[não associado]',
							'new' => testVal($log->new_value, $key) ? Vehicle::find($log->new_value[$key])->plate : '[não associado]',
							'user' => $log->user->username,
							'date' => date('d/m/Y H:i:s', strtotime($log->updated_at))
						);
						break;
					case 'company_id':
						$audit[] = array(
							'label' => $labels[$key],
							'old' => testVal($log->old_value, $key) ? Company::find($log->old_value[$key])->name : '[não associado]',
							'new' => testVal($log->new_value, $key) ? Company::find($log->new_value[$key])->name : '[não associado]',
							'user' => $log->user->username,
							'date' => date('d/m/Y H:i:s', strtotime($log->updated_at))
						);
						break;
					default:
						$audit[] = array(
							'label' => $labels[$key],
							'old' => testVal($log->old_value, $key) ? $log->old_value[$key] : '',
							'new' => testVal($log->new_value, $key) ? $log->new_value[$key] : '',
							'user' => $log->user->username,
							'date' => date('d/m/Y H:i:s', strtotime($log->updated_at))
						);
						break;
				}
			}
		}
		$grid = \DataGrid::source($audit);
		$grid->attributes(array("class"=>"table table-striped .table-condensed"));
		$grid->add('label', 'Campo');
		$grid->add('old', 'Valor Anterior');
		$grid->add('new', 'Novo Valor');
		$grid->add('user', 'Usuário');
		$grid->add('date', 'Data/Hora');
		$grid->orderBy('date','DESC');
		$grid->paginate(10);
		return view('layouts.audit', compact('grid'));
	}

}