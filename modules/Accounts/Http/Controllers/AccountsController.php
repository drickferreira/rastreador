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
			$form->text('phone1','Telefone')->rule('max:15');
			$form->text('phone2','Telefone')->rule('max:15');
			$form->textarea('description','Observações');
			$form->checkbox('active','Ativo');
			if ($form->status == 'create'){
				$form->label('Novo Cliente');
			} else {
				$form->label("Cliente");
			}
			$form->saved(function () use ($form){
				return redirect('accounts')->with('message','Registro salvo com sucesso!'); 
      });
			if ($form->status == "show"){
				$form->link("#", "Registro de Alterações", "TR", ['onClick'=>"MyWindow=window.open('audit/".$form->model->id."','MyWindow','width=800,height=400'); return false;"]);
			}
			$form->build();
			return $form->view('accounts::create', compact('form'));
		} else {
			return redirect()->back()->with('error', 'Você não tem permissão para acessar esse módulo!');
		}
	}
	
	public function audit($id)
	{
		$account = Account::findOrFail($id);
		$logs = $account->logs->sortByDesc('id');
		$audit = array();
		$labels = array(
			'name' => 'Nome',
			'cpf_cnpj' => 'CPF/CNPJ',
			'phone1' => 'Telefone',
			'phone2' => 'Telefone',
			'description' => 'Observações',
		);
		if ($logs)
		foreach($logs as $log)
		{
			foreach( $log->new_value as $key => $value)
			{
				$audit[] = array(
					'label' => $labels[$key],
					'old' => testVal($log->old_value, $key) ? $log->old_value[$key] : '',
					'new' => testVal($log->new_value, $key) ? $log->new_value[$key] : '',
					'user' => $log->user->username,
					'date' => date('d/m/Y H:i:s', strtotime($log->updated_at))
				);
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