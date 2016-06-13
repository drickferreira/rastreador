<?php namespace Modules\Accounts\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Modules\Accounts\Entities\Account;
use Illuminate\Http\Request;
use Auth;

class AccountsController extends Controller {
	
	public function index()
	{
		if (Auth::user()->isAdmin() || Auth::user()->isSuperAdmin()) {
			$filter = \DataFilter::source(Account::where('company_id', Auth::user()->company_id));
			$filter->add('name','Nome', 'text');
			$filter->add('cpf_cnpj','CPF/CNPJ', 'text');
			$filter->add('hasvehicle', 'Veículo', 'select')
				->options(array(0 => 'Todos', 1 => 'Com Veículos', 2 => 'Sem Veículos'))
				->scope('hasvehicle');
			$filter->submit('Buscar');
			$filter->reset('Limpar');
			$filter->build();

			$grid = \DataGrid::source($filter);
			$grid->label('Clientes');
			$grid->attributes(array("class"=>"table table-striped"));
			$grid->add('name','Nome', true);
			$grid->add('cpf_cnpj','CPF/CNPJ', true);
			$grid->add('phone1','Telefone'); 
			$grid->add('phone2','Telefone'); 
			$grid->edit('accounts/edit', 'Ações','show|modify|delete');
			$grid->link('accounts/edit',"Novo Cliente", "TR");
			$grid->orderBy('name','asc');
			return view('accounts::index', compact('filter', 'grid'));
		} else {
			return view('errors.503');
		}
	}

	public function edit()
	{
		if (Auth::user()->isAdmin() || Auth::user()->isSuperAdmin()) {
			$form = \DataEdit::source(new Account);
			$form->link("accounts","Voltar", "TR")->back();
			$form->set('company_id', Auth::user()->company_id);
			$form->text('name','Nome')->rule('required|min:5');
			$form->text('cpf_cnpj','CPF / CNPJ')->rule('required|min:14')->unique(null, null ,'company_id,'.Auth::user()->company_id);
			$form->text('phone1','Telefone');
			$form->text('phone2','Telefone');
			$form->textarea('description','Observações');
			if ($form->status == 'create'){
				$form->label('Novo Cliente');
			} else {
				$form->label("Cliente");
			}
			$form->saved(function () use ($form){
				return redirect('accounts')->with('message','Registro salvo com sucesso!'); 
      });
			$form->build();
			return $form->view('accounts::create', compact('form'));
		} else {
			return redirect()->back()->with('error', 'Você não tem permissão para acessar esse módulo!');
		}
	}

	
}