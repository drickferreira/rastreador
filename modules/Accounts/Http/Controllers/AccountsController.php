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
			$filter->text('src','Search')->scope('freesearch');
			$filter->build();

			$grid = \DataGrid::source($filter);
			$grid->attributes(array("class"=>"table table-striped"));
			$grid->add('name','Nome', true);
			$grid->add('cpf_cnpj','CPF/CNPJ', true);
			$grid->add('phone1','Telefone'); 
			$grid->add('phone2','Telefone'); 
			$grid->edit('accounts/edit', 'Ações','show|modify|delete');
			$grid->link('accounts/edit',"Novo Associado", "TR");
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
			$form->select('company_id', 'Empresa')->options(Auth::user()->Company()->lists("name", "id")->all());
			$form->text('name','Nome')->rule('required|min:5');
			$form->text('cpf_cnpj','CPF / CNPJ');
			$form->text('phone1','Telefone');
			$form->text('phone2','Telefone');
			$form->textarea('description','Observações');
			return $form->view('accounts::create', compact('form'));
		} else {
			return view('errors.503');
		}
	}

	
}