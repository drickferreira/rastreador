<?php namespace Modules\Companies\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Modules\Companies\Entities\Company;
use Illuminate\Http\Request;
use Auth;

class CompaniesController extends Controller {
	
	public function index()
	{
		if (Auth::user()->isSuperAdmin()) {
			$filter = \DataFilter::source(new Company);
			$filter->add('name','Nome', 'text');
			$filter->add('cnpj','CNPJ', 'text')->attributes(array("data-mask"=>"00.000.000/0000-00"));
			$filter->submit('Buscar');
			$filter->reset('Limpar');
			$filter->build();

			$grid = \DataGrid::source($filter);
			$grid->label('Empresas');
			$grid->attributes(array("class"=>"table table-striped"));
			$grid->add('name','Nome', true);
			$grid->add('cnpj','CPF/CNPJ', true);
			$grid->edit('companies/edit', 'Ações','show|modify|delete');
			$grid->link('companies/edit',"Nova Empresa", "TR");
			$grid->orderBy('name','asc');
			return view('companies::index', compact('filter', 'grid'));
		} else {
			return view('errors.503');
		}
	}
	public function edit()
	{
		if (Auth::user()->isSuperAdmin()) {
			$form = \DataEdit::source(new Company);
			$form->link("companies","Voltar", "TR")->back();
			$form->text('name','Nome')->rule('required|min:5');
			$form->text('cnpj','CPF / CNPJ')->attributes(array("data-mask"=>"00.000.000/0000-00"));
			$form->text('insc','Insc. Estadual');
			$form->text('phone1','Telefone')->attributes(array("data-mask"=>"(00)0000-00000"));
			$form->text('phone2','Telefone')->attributes(array("data-mask"=>"(00)0000-00000"));
			$form->text('email','E-mail')->rule('email');
			$form->text('address','Endereço');
			$form->text('number','Número');
			$form->text('comp','Complemento');
			$form->text('quarter','Bairro');
			$form->text('city','Cidade');
			$form->text('state','Estado');
			$form->text('country','País');
			$form->text('postalcode','CEP')->attributes(array("data-mask"=>"##.###-###"));
			if ($form->status == 'create'){
				$form->label('Nova Empresa');
			} else {
				$form->label("Empresa");
			}
			$form->saved(function () use ($form){
				return redirect('companies')->with('message','Registro salvo com sucesso!'); 
      });
			if ($form->status == "show"){
				$form->link("#", "Registro de Alterações", "TR", ['onClick'=>"MyWindow=window.open('audit/".$form->model->id."','MyWindow','width=800,height=400'); return false;"]);
			}
			$form->build();
			return $form->view('companies::edit', compact('form'));
		} else {
			return view('errors.503');
		}
	}
		
	public function audit($id)
	{
		$company = Company::findOrFail($id);
		$logs = $company->logs;
		$audit = array();
		$labels = array(
			'name' => 'Nome',
			'cnpj' => 'CNPJ',
			'insc' => 'Inscrição Estadual',
			'phone1' => 'Telefone',
			'phone2' => 'Telefone',
			'email' => 'E-mail',
			'address' => 'Endereço',
			'number' => 'Número',
			'comp' => 'Complemento',
			'quarter' => 'Bairro',
			'city' => 'Cidade',
			'state' => 'Estado',
			'country' => 'País',
			'postalcode' => 'CEP'
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