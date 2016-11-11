<?php namespace Modules\Vehicles\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Modules\Vehicles\Entities\Vehicle;
use Modules\Accounts\Entities\Account;
use Modules\Commands\Entities\Command;
use Modules\CommandParameters\Entities\CommandParameter;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class VehiclesController extends Controller {
	
	public function index()
	{
		if (Auth::user()->isAdmin() || Auth::user()->isSuperAdmin()) {
			$filter = \DataFilter::source(Vehicle::with('Device','Account')->whereHas('Account', function ($query) {
					$query->where('accounts.active',true)
								->where('company_id', Auth::user()->company_id);
			}));
			$filter->add('Account.name', 'Cliente', 'text')
				->scope('hasaccountname');
			$filter->add('plate','Placa', 'text')
				->scope(function ($query, $value) {
					return $query->whereRaw("plate LIKE '%".strtoupper($value)."%'");
   		});
			$filter->add('vehicle_data','Marca, Modelo, Cor', 'text')
						 ->scope(function ($query, $value) {
							 $args = explode(',', $value);
							 foreach($args as $arg){
								 $query = $query->whereRaw("(brand like '%".trim($arg)."%' OR model like '%".trim($arg)."%' OR color like '%".trim($arg)."%')");
							 }
							 return $query;
						 });
			$filter->add('active','Ativo','select')
						 ->options(['' => 'Ativos/Inativos', 'A' => 'Ativos', 'I' => 'Inativos'])
						 ->scope(function ($query, $value) {
								if ($value == ''){
									return $query;
								} elseif ($value == 'A'){
									return $query->where('vehicles.active', true);
								} elseif ($value == 'I'){
									return $query->where('vehicles.active', false);
								}
						 });
			$filter->add('panic','Pânico','select')
						 ->options(['' => 'Pânico Ativado/Desativado', 'A' => 'Ativado', 'I' => 'Desativado'])
						 ->scope(function ($query, $value) {
								if ($value == ''){
									return $query;
								} elseif ($value == 'A'){
									return $query->where('panic', true);
								} elseif ($value == 'I'){
									return $query->where('panic', false);
								}
						 });
			$filter->add('hasdevice', 'Atribuído', 'select')
				->options(array(0 => 'Com/Sem Rastreador', 1 => 'Com Rastreador', 2 => 'Sem Rastreador'))
				->scope('hasdevice');
			$filter->submit('Buscar');
			$filter->reset('Limpar');
			$filter->build();
			
			$grid = \DataGrid::source($filter);
			$grid->label('Veículos');
			$grid->attributes(array("class"=>"table table-striped"));
			$grid->add('Account.name','Cliente', 'account_id');
			$grid->add('plate','Placa', true);
			$grid->add('fullname','Identificação', true);
			$grid->add('panic','Antifurto');

			$grid->row(function ($row) {
				$value = $row->cells[3]->value;
				$class = 'primary';
				$title = 'Desativado';
				$label = 'Ativar Antifurto';
				if ($value == true){
					$class = 'danger';
					$title = 'Ativado';
					$label = 'Desativar Antifurto';
				}
				$row->cells[3]->value = "<a class='btn btn-$class btn-xs' title='$label' href='/vehicles/antitheft/".$row->data->id."'>$title</a>";
			});



			$grid->add('Device.serial','Aparelho'); 
			$grid->edit('vehicles/edit', 'Ações','show|modify');
			$grid->link('vehicles/edit',"Novo Veículo", "TR");
			$grid->paginate(10);

			return view('vehicles::index', compact('filter', 'grid'));
		} else {
			return redirect()->back()->with('error', 'Você não tem permissão para acessar esse módulo!');
		}
	}

	public function edit()
	{
		if (Auth::user()->isAdmin() || Auth::user()->isSuperAdmin()) {
			$form = \DataEdit::source(new Vehicle);
			$form->link("vehicles","Veículos", "TR")->back();
			if ($form->status == 'create'){
				$form->label('Novo Veículo');
			} else {
				$form->label("Veículo");
			}
			$options = Account::where('company_id', Auth::user()->company_id)->orderBy('name')->lists("name", "id")->all();
			$form->add('Account.name', 'Cliente', 'autocomplete')->options($options)->rule('required');
			$id = '00000000-0000-0000-0000-000000000000';
			if ($form->status != "create"){
				$id = $form->model->id;
			}
			$form->text('plate','Placa')->rule('required|min:8|unique:vehicles,plate,'.$id.',id,deleted_at,NULL')->attributes(array("data-mask"=>"AAA-0000"));
			$form->text('brand','Marca');
			$form->text('model','Modelo'); 
			$form->text('year','Ano')->attributes(array("data-mask"=>"0000")); 
			$form->text('color','Cor');
			if ($form->status == "create"){
				$form->checkbox('active','Ativo')->insertValue(1);
			} elseif ($form->status == "modify"){
				$device = $form->model->Device()->get();
				if ($device){
					$form->checkbox('active','Ativo')->mode('readonly');
				} else {
					$form->checkbox('active','Ativo');
				}
			} else {
				$form->checkbox('active','Ativo');
			}
			$form->saved(function () use ($form){
				return redirect('vehicles')->with('message','Registro salvo com sucesso!'); 
      });
			if ($form->status == "show"){
				$form->link("#", "Registro de Alterações", "TR", ['onClick'=>"MyWindow=window.open('audit/".$form->model->id."','MyWindow','width=800,height=400'); return false;"]);
			}
			$form->build();
			return $form->view('vehicles::create', compact('form'));
		} else {
			return redirect()->back()->with('error', 'Você não tem permissão para acessar esse módulo!');
		}
	}
	
	public function getAccountlist()
	{
		return Account::where('active', true)->where("name","like", \Input::get("q")."%")->take(10)->get();
	}

		
	public function audit($id)
	{
		$vehicle = Vehicle::findOrFail($id);
		$logs = $vehicle->logs->sortByDesc('id');
		$audit = array();
		$labels = array(
			'plate' => 'Placa',
			'brand' => 'Marca',
			'model' => 'Modelo',
			'year' => 'Ano',
			'color' => 'Cor',
			'active' => 'Ativo',
			'account_id' => 'Cliente',
			'panic' => 'Anti-furto',
		);
		if ($logs)
		foreach($logs as $log)
		{
			foreach( $log->new_value as $key => $value)
			{
					switch ($key){
						case 'active':
							$audit[] = array(
								'label' => $labels[$key],
								'old' => testVal($log->old_value, $key) ? "Ativo" : "Inativo",
								'new' => testVal($log->new_value, $key) ? "Ativo" : "Inativo",
								'user' => $log->user->username,
								'date' => date('d/m/Y H:i:s', strtotime($log->updated_at))
							);
							break;
						case 'panic':
							$audit[] = array(
								'label' => $labels[$key],
								'old' => testVal($log->old_value, $key) ? "Ativo" : "Inativo",
								'new' => testVal($log->new_value, $key) ? "Ativo" : "Inativo",
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
		$grid->add('user', 'Alterado por');
		$grid->add('date', 'Data/Hora da Alteração');
		$grid->paginate(10);
		return view('layouts.audit', compact('grid'));
	}
	
	public function antitheft($id) {
		$vehicle = Vehicle::findOrFail($id);
		$time = 180; //tempo default de transmissão em movimento - 3 minutos
		
		if ($vehicle->panic) {
			$vehicle->panic = false;
			$vehicle->save();
		} else {
			$vehicle->panic = true;
			$vehicle->save();
			$time = 30; //tempo de transmissão em movimento 30 segundos
		}

		$device = $vehicle->Device;
		$now = Carbon::now()->format('U');
		$timeout = Carbon::now()->addMonth()->toDateTimeString();
		$id_command = 'SET_REPORT_TIME_MOVING_'.$device->model.'_'.$device->serial.'_'.$now;
		$type = 51;
		
		//Salvando o Comando na base de dados
		$new_command = new Command;
		$new_command->id_command = $id_command;
		$new_command->type = $type;
		
		$device->Commands()->save($new_command);
		
		$xml = new \DOMDocument( "1.0", "iso-8859-1" );
		$xml->formatOutput = true;
		$commands = $xml->createElement("COMMANDS");
		$command = $xml->createElement('COMMAND');
		$element = $xml->createElement('PROTOCOL', $device->model);
		$command->appendChild($element);
		$element = $xml->createElement('SERIAL', $device->serial);
		$command->appendChild($element);
		$element = $xml->createElement('ID_COMMAND', $id_command);
		$command->appendChild($element);
		$element = $xml->createElement('TYPE', $type);
		$command->appendChild($element);
		$element = $xml->createElement('ATTEMPTS', 10);
		$command->appendChild($element);
		$element = $xml->createElement('COMMAND_TIMEOUT', $timeout);
		$command->appendChild($element);
		$element = $xml->createElement('PARAMETERS');

		//parametro 1
		$par1 = new CommandParameter;
		$par1->parameter_id = 'SET_REPORT_TIME_MOVING';
		$par1->value = 1;
		$new_command->Parameters()->save($par1);
		
		$parameter = $xml->createElement('PARAMETER');
		$parameter_id = $xml->createElement('ID', 'SET_REPORT_TIME_MOVING');
		$parameter_value = $xml->createElement('VALUE', 1);
		$parameter->appendChild($parameter_id);
		$parameter->appendChild($parameter_value);
		$element->appendChild($parameter);

		//parametro 2
		$par2 = new CommandParameter;
		$par2->parameter_id = 'REPORT TIME MOVING';
		$par2->value = $time;
		$new_command->Parameters()->save($par2);
		
		$parameter = $xml->createElement('PARAMETER');
		$parameter_id = $xml->createElement('ID', 'REPORT TIME MOVING');
		$parameter_value = $xml->createElement('VALUE', $time);
		$parameter->appendChild($parameter_id);
		$parameter->appendChild($parameter_value);
		$element->appendChild($parameter);

		$command->appendChild($element);
		$commands->appendChild($command);
		$xml->appendChild($commands);
		Storage::disk('ftp')->put("commands/$id_command.cmd", $xml->saveXML());
		return redirect('/vehicles')->with('message','Comando Enviado!');  
//		printf ("<pre>%s</pre>", htmlentities ($xml->saveXML()));
	}

}