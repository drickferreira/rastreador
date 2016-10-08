<?php namespace Modules\Commands\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Modules\Commands\Entities\Command;
use Modules\CommandParameters\Entities\CommandParameter;
use Modules\CommandsResponse\Entities\CommandsResponse;
use Modules\Devices\Entities\Device;
use Modules\Companies\Entities\Company;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Storage;

class CommandsController extends Controller {
	
	public function index()
	{
		$filter = \DataFilter::source(Command::with('Device'));
		$filter->add('id_command', 'Identificação', 'select')
			->option('','')
			->options(config('commands_names'))
			->scope( function ($query, $value) {
				return $query->whereRaw("id_command LIKE '".$value."%'");
		});
		$filter->add('dataini','Data Inicial', 'date')->format('d/m/Y')->scope(function ($query, $value)  {
			$test = (bool)strtotime($value);
			$data = $value." 00:00:00";
			if ($test)
      	return $query->whereRaw("date_trunc('day', created_at) >= ?", array($data));  
			else
				return $query;
		});
		$filter->add('datafin','Data Final', 'date')->format('d/m/Y')->scope(function ($query, $value)  {
			$test = (bool)strtotime($value);
			$data = $value." 00:00:00";
			if ($test)
	      return  $query->whereRaw("date_trunc('day', created_at) <= ?", array($data));  
			else
				return $query;
		});


		$filter->add('Device.serial','Serial', 'text')->clause('where')->operator('=');
		$filter->submit('Buscar');
		$filter->reset('Limpar');
		$filter->build();
		
		$grid = \DataGrid::source($filter);
		$grid->label('Comandos');
		$grid->attributes(array("class"=>"table table-striped"));
		$grid->add('id_command','Identificação', true);
		$grid->add('created_at|strtotime|date[d/m/Y H:i:s]','Data do Envio', true);
		$grid->add('Device.serial','Serial');
		$grid->add('laststatus','Último Status');
		$grid->edit('commands/edit', 'Ações','show');
		$grid->link('commands/mass',"Novo Comando em Massa", "TR");
		
		$grid->paginate(10);
		return view('commands::index', compact('filter', 'grid'));
	}
	
	public function edit(Request $request)
	{
		$id = $request->show;
		$command = Command::find($id);
		$responses = $command->Responses->all();
		$form = \DataForm::create();
		//dd($command->Device->serial);
		$form->label = $command->id_command;
		$form->add('device','Aparelho', 'text')->insertValue($command->Device->serial)->mode('readonly');
		$form->add('created_at', 'Data do envio', 'text')->insertValue($command->created_at)->mode('readonly');
		$form->link("/commands","Voltar", "TR")->back();
		return $form->view('commands::edit', compact('form', 'responses'));
	}
	
	public function getCommand($id){
		$device = Device::find($id);
		$commands = config('commands_names');
		return view('commands::new', compact('device', 'commands'));
	}
	
	public function getArguments(Request $request){
		$device_id = $request->device_id;
		$device = Device::find($device_id);
		$id_command = $request->id_command;
		$names = config('commands_names');
		$template = config('commands_syntax.'.$id_command);
		$form = \DataForm::create();
		$form->add('device_id', '', 'hidden')->insertValue($device_id);
		$form->add('device_name', 'Aparelho', 'text')->insertValue($device->serial)->mode('readonly');
		$form->add('type', '', 'hidden')->insertValue(array_get($template, 'TYPE'));
		$form->link("/commands/send/$device_id", 'Voltar', 'TR')->back();
		$form->label($names[$id_command]);
		$params = array_get($template, 'PARAMETERS');
		$p = 0;
		foreach ($params as $param){
			$form->add('PAR_'.$p, '', 'hidden')->insertValue($param['ID']);
			if ($param['VALUE'] !== ''){
				if (is_array($param['VALUE'])){
					$form->add('VAL_'.$p, $param['LABEL'], 'select')->options($param['VALUE']);
				} else {
					$form->add('VAL_'.$p, '', 'hidden')->insertValue($param['VALUE']);
				}
			} else {
				$form->add('VAL_'.$p, $param['LABEL'], 'text');
			}
			$p++;
		}
		$form->add('param_count', '', 'hidden')->insertValue($p);
		//dd($template);
		$form->submit('Salvar');	
		return $form->view('commands::edit', compact('form'));
	}

	public function postArguments(Request $request){
		//dd($request->all());
		$device_id = $request->device_id;
		$device = Device::find($device_id);
		$now = Carbon::now()->format('U');
		$timeout = Carbon::now()->addMonth()->toDateTimeString();
		$id_command = $request->id_command.'_'.$device->model.'_'.$device->serial.'_'.$now;
		$type = $request->type;
		$count = $request->param_count;
		
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
		$element = $xml->createElement('TYPE', $request->type);
		$command->appendChild($element);
		$element = $xml->createElement('ATTEMPTS', 10);
		$command->appendChild($element);
		$element = $xml->createElement('COMMAND_TIMEOUT', $timeout);
		$command->appendChild($element);
		$element = $xml->createElement('PARAMETERS');
		for ($p = 0; $p < $count; $p++){
			$par = 'PAR_'.$p;
			$val = 'VAL_'.$p;
			
			$new_parameter = new CommandParameter;
			$new_parameter->parameter_id = $request->$par;
			$new_parameter->value = $request->$val;
			
			$new_command->Parameters()->save($new_parameter);
			
			$parameter = $xml->createElement('PARAMETER');
			$parameter_id = $xml->createElement('ID', $request->$par);
			$parameter_value = $xml->createElement('VALUE', $request->$val);
			$parameter->appendChild($parameter_id);
			$parameter->appendChild($parameter_value);
			$element->appendChild($parameter);
		}
		$command->appendChild($element);
		$commands->appendChild($command);
		$xml->appendChild($commands);
		Storage::disk('ftp')->put("commands/$id_command.cmd", $xml->saveXML());
		return redirect('/devices')->with('message','Comando Enviado!');  
		//printf ("<pre>%s</pre>", htmlentities ($xml->saveXML()));
	}	
	
	public function MassCommands()
	{
		$commands = config('commands_names');
		return view('commands::mass', compact('commands'));
	}

	public function massArguments(Request $request){
		$id_command = $request->id_command;
		$template = config('commands_syntax.'.$id_command);
		$params = array_get($template, 'PARAMETERS');
		$name = config('commands_names.'.$id_command);
		$request->session()->regenerate();
		$request->session()->put('id_command', $id_command);
		return view('commands::massedit', compact('params', 'name'));
	}	
	
	public function getMassDevices(Request $request){
		//dd($request->all());
		//dd($request->session()->all());
		$count = $request->param_count;
		$params = array();
		$values = array();
		for ($p = 0; $p < $count; $p++){
			$par = 'PAR_'.$p;
			$val = 'VAL_'.$p;
			$params[$par] = $request->$par;
			$values[$val] = $request->$val;
		}
		$request->session()->put('params', $params);
		$request->session()->put('values', $values);
		$devices = config("dropdown.devices");
		$companies = Company::lists("name", "id")->all();
		return view('commands::massdevices', compact('devices', 'companies'));
	}

	public function getDeviceList(Request $request){
		$model = $request->model;
		$company_id = $request->company_id;
		$hasvehicle = $request->hasvehicle;
		$devicesquery = Device::hasvehicle($hasvehicle);
		if ($company_id != '')
			$devicesquery = $devicesquery->where('company_id', $company_id);
		if ($model != '')
			$devicesquery = $devicesquery->where('model', $model);
		$result = $devicesquery->orderBy('serial')->lists("serial", "id")->all();
		return json_encode($result);
	}
	
	public function createMass(Request $request){
		//dd($request->session()->all());
		//dd($request->all());
		
		$params = $request->session()->get('params');
		$values = $request->session()->get('values');
		$command_id = $request->session()->get('id_command');
		$type = config('commands_syntax.'.$command_id.'.TYPE');
		$count = count($params);

		foreach ($request->ids as $device_id){
			$device = Device::find($device_id);
			$now = Carbon::now()->format('U');
			$timeout = Carbon::now()->addMonth()->toDateTimeString();
			$id_command = $command_id.'_'.$device->model.'_'.$device->serial.'_'.$now;
			
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
			for ($p = 0; $p < $count; $p++){
				$par = 'PAR_'.$p;
				$val = 'VAL_'.$p;
				
				$new_parameter = new CommandParameter;
				$new_parameter->parameter_id = $params[$par];
				$new_parameter->value = $values[$val];
				
				$new_command->Parameters()->save($new_parameter);
				
				$parameter = $xml->createElement('PARAMETER');
				$parameter_id = $xml->createElement('ID', $params[$par]);
				$parameter_value = $xml->createElement('VALUE', $values[$val]);
				$parameter->appendChild($parameter_id);
				$parameter->appendChild($parameter_value);
				$element->appendChild($parameter);
			}
			$command->appendChild($element);
			$commands->appendChild($command);
			$xml->appendChild($commands);
			Storage::disk('ftp')->put("commands/$id_command.cmd", $xml->saveXML());
			//printf ("<pre>%s</pre>", htmlentities ($xml->saveXML()));
		}
		return redirect('/commands')->with('message','Comandos Enviados!');  
			
	}
}