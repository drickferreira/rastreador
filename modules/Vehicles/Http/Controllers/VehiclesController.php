<?php namespace Modules\Vehicles\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Modules\Vehicles\Entities\Vehicle;
use Modules\Accounts\Entities\Account;
use Illuminate\Http\Request;
use Auth;

class VehiclesController extends Controller {
	
	public function index()
	{
		if (Auth::user()->isAdmin() || Auth::user()->isSuperAdmin()) {
			$filter = \DataFilter::source(Vehicle::with('Account')->whereHas('Account', function ($query) {
					$query->where('company_id', Auth::user()->company_id);
			}));
			$filter->add('plate','Placa', 'text')
				->scope( function ($query, $value) {
					return $query->whereRaw("plate LIKE '%".strtoupper($value)."%'");
   		});
			$filter->add('brand','Marca', 'text');
			$filter->add('model','Modelo', 'text');
			$filter->add('year','Ano', 'text');
			$filter->add('color','Cor', 'text');
			$filter->add('hasdevice', 'Atribuído', 'select')
				->options(array(0 => 'Todos', 1 => 'Com Rastreador', 2 => 'Sem Rastreador'))
				->scope('hasdevice');
			$filter->submit('Buscar');
			$filter->reset('Limpar');
			$filter->build();
			
			$grid = \DataGrid::source($filter);
			$grid->label('Veículos');
			$grid->attributes(array("class"=>"table table-striped"));
			$grid->add('Account.name','Cliente', 'account_id');
			$grid->add('plate','Placa', true);
			$grid->add('brand','Marca', true);
			$grid->add('model','Modelo', true); 
			$grid->add('year','Ano', true); 
			$grid->add('color','Cor', true); 
			$grid->add('assigneddevice','Aparelho'); 
			$grid->edit('vehicles/edit', 'Ações','show|modify|delete');
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
			$form->add('Account.name', 'Cliente', 'autocomplete')->options($options);
			$form->text('plate','Placa')->rule('required|min:8')->unique()->attributes(array("data-mask"=>"AAA-0000"));
			$form->text('brand','Marca');
			$form->text('model','Modelo'); 
			$form->text('year','Ano')->attributes(array("data-mask"=>"0000")); 
			$form->text('color','Cor');
			$form->checkbox('active','Ativo');
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
		return Account::where("name","like", \Input::get("q")."%")->take(10)->get();
	}

		
	public function audit($id)
	{
		$vehicle = Vehicle::findOrFail($id);
		$logs = $vehicle->logs;
		$audit = array();
		$labels = array(
			'plate' => 'Placa',
			'brand' => 'Marca',
			'model' => 'Modelo',
			'year' => 'Ano',
			'color' => 'Cor',
			'active' => 'Ativo',
			'account_id' => 'Cliente'
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

}