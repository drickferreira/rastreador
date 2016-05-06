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
			$filter->add('plate','Placa', 'text');
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
			return view('errors.503');
		}
	}

	public function edit()
	{
		if (Auth::user()->isAdmin() || Auth::user()->isSuperAdmin()) {
			$form = \DataEdit::source(new Vehicle);
			$form->link("vehicles","Voltar", "TR")->back();
			$options = Account::where('company_id', Auth::user()->company_id)->orderBy('name')->lists("name", "id")->all();
			$form->add('Account.name', 'Cliente', 'autocomplete')->options($options);
			$form->text('plate','Placa')->attributes(array("data-mask"=>"AAA-0000"));
			$form->text('brand','Marca');
			$form->text('model','Modelo'); 
			$form->text('year','Ano')->attributes(array("data-mask"=>"0000")); 
			$form->text('color','Cor');
			$form->checkbox('active','Ativo');
			 
			return $form->view('vehicles::create', compact('form'));
		} else {
			return view('errors.503');
		}
	}
	
	public function getAccountlist()
	{
		return Account::where("name","like", \Input::get("q")."%")->take(10)->get();
	}



}