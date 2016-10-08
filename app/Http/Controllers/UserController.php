<?php 

namespace App\Http\Controllers;

use Auth;
use Hash;
use App\User;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Password;
use Modules\Companies\Entities\Company;
use Modules\Vehicles\Entities\Vehicle;
use Illuminate\Http\Request;

class UserController extends Controller {
	
    public function index()
    {
			if (Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() ) {
				if (Auth::user()->isSuperAdmin()) {
					$filter = \DataFilter::source(User::with('Company'));
					$filter->add('username', 'Nome de Usuário', 'text')
								 ->scope( function ($query, $value) {
									 return $query->whereRaw("upper(username) LIKE '%".strtoupper($value)."%'");
					});
					$filter->add('name', 'Nome', 'text')
								 ->scope( function ($query, $value) {
									 return $query->whereRaw("upper(name) LIKE '%".strtoupper($value)."%'");
					});
					$filter->add('company_id','Empresa', 'select')
						->option('','Empresa')
						->options(Company::lists("name", "id")->all());
					$filter->submit('Buscar');
					$filter->reset('Limpar');
					$filter->build();

					$grid = \DataGrid::source($filter);
					$grid->label('Usuários');
					$grid->attributes(array("class"=>"table table-striped"));
					$grid->add('username','Usuário', true);
					$grid->add('name','Nome', true);
					$grid->add('Company.name','Empresa', 'company_id');
					$grid->add('{{ fieldValue("all_roles", $role) }}','Perfil', 'role');
					$grid->edit('user/edit', 'Ações','show|modify|delete');
					$grid->link('user/edit',"Novo Usuário", "TR");
				} else {
					$filter = \DataFilter::source(User::with('Company')->where('company_id', Auth::user()->company_id));
					$filter->add('username', 'Nome de Usuário', 'text')
								 ->scope( function ($query, $value) {
									 return $query->whereRaw("upper(username) LIKE '%".strtoupper($value)."%'");
					});
					$filter->add('name', 'Nome', 'text')
								 ->scope( function ($query, $value) {
									 return $query->whereRaw("upper(name) LIKE '%".strtoupper($value)."%'");
					});
					$filter->submit('Buscar');
					$filter->reset('Limpar');
					$filter->build();

					$grid = \DataGrid::source($filter);
					$grid->label('Usuários');
					$grid->attributes(array("class"=>"table table-striped"));
					$grid->add('username','Usuário', true);
					$grid->add('name','Nome', true);
					$grid->add('role','Perfil');
					$grid->row(function ($row) {
						$role = $row->cells[2]->value;
						$classes = array( 10 => 'primary', 20 => 'success', 30 => 'default');
						if ($role == 40) {
							$row->cells[2]->value = '<a class="btn btn-danger btn-xs" title="Veículos" href="/user/vehicles/'.$row->data->id.'">'.fieldValue('all_roles', $row->cells[2]->value).' <i class="fa fa-car"></i></a>';
						} else {
							$row->cells[2]->value = '<button class="btn btn-'.$classes[$role].' btn-xs">'.fieldValue('all_roles', $row->cells[2]->value).'</button>';
						}
					});
					$grid->edit('user/edit', 'Ações','show|modify|delete');
					$grid->link('user/edit',"Novo Usuário", "TR");
				}
				$grid->orderBy('name','asc');
				return view('user.index', compact('filter', 'grid'));
			} else {
				return redirect()->back()->with('error', 'Você não tem permissão para acessar esse módulo!');
			}
    }

    public function edit()
    {
			if (Auth::user()->isSuperAdmin() || Auth::user()->isAdmin()) {
				$form = \DataEdit::source(new User);
				$form->link("user","Usuários", "TR")->back();
				$form->text('username','Usuário')->rule('required|alpha_dash|max:20')->unique();
				$form->text('name','Nome')->rule('required|max:255');
				$form->text('email','Email')->rule('required|email|max:255')->unique();
				if (Auth::user()->isSuperAdmin()){
					$form->select('company_id','Empresa')->option("","")->options(Company::lists('name', 'id')->all());
					$form->select('role','Perfil')->options(config("dropdown.roles"));
				} else {
					$form->set('company_id',Auth::user()->company_id);
					$form->select('role','Perfil')->options(config("dropdown.company_roles"));
				}
				//dd($form);
				if ($form->status == 'show'){
					$form->link('user/reset?email='.$form->model->email, 'Mudar Senha', 'TR');
				}
				if ($form->status == 'create'){
					$form->label('Novo Usuário');
					$string = bcrypt(str_random(6));
					$form->set('password', $string);
					$form->set('password_confirmation', $string);
				} else {
					$form->label("Usuário");
				}
				$form->saved(function () use ($form) {
			    return redirect('user')->with('message','Usuário salvo com sucesso!'); 
        });
				if ($form->status == "show"){
					$form->link("#", "Registro de Alterações", "TR", ['onClick'=>"MyWindow=window.open('audit/".$form->model->id."','MyWindow','width=800,height=400'); return false;"]);
				}
				$form->build();
				return $form->view('user.edit', compact('form'));
			} else {
				return redirect()->back()->with('error', 'Você não tem permissão para acessar esse módulo!');
			}
    }
		
		public function vehicles($id)
		{
			$user = User::find($id);
			$form = \DataEdit::source($user);
			$form->label('Atribuir Placas');
			$form->link("/user","Cancelar", "TR")->back();
			$form->text('username','Usuário')->mode('readonly');
			$form->text('name','Nome')->mode('readonly');
			$form->tags('Vehicles.plate', 'Veículos')->remote("plate", "id", "/user/autocomplete");
			return $form->view('user.vehicles', compact('form'));
		}

		public function getAutocomplete()
    {
        return Vehicle::where("plate","like", strtoupper(\Input::get("q"))."%")
				->whereHas('Account', function ($query) {
			    $query->where('company_id', Auth::user()->company_id);
				})->take(10)
        ->select(\DB::raw('plate, id'))->get();
    }

		
		public function reset(Request $request)
		{
			$control = new PasswordController;
			view()->composer('emails.password', function($view) {
          $view->with(['old_user'  => true]);
       });
			$result = $control->postEmail($request);
			return redirect('user')->with('message','A mensagem para troca de senha foi enviada!'); 
		}
		
		public function getpassword()
    {
			$form = \DataForm::create();
			$form->add('user_id', '', 'hidden')->insertValue(Auth::user()->id);
			$form->add('old_password', 'Senha Antiga', 'password');
			$form->add('password', 'Senha Nova', 'password');
			$form->add('password_confirmation', 'Confirme a Senha', 'password');
			$form->label('Alterar Senha');
			$form->submit('Atualizar');
			$form->build();
			return $form->view('user.password', compact('form'));
    }
		
		public function setpassword(Request $request){
			$this->validate($request, [
        'old_password' => 'required',
        'password' => 'required|confirmed|min:6',
	    ]);
			$user = User::findOrFail($request->user_id);
			if (Hash::check($request->old_password, $user->getAuthPassword())) {
				if ($request->password == $request->password_confirmation){
					$user->password = bcrypt($request->password);
					$user->save();
					return redirect('/')->with('message','Senha atualizada!');
				} else {
					return redirect()->back()->with('error', 'A senha e a confirmação de senha não conferem!'); 
				} 
			} else {
				return redirect()->back()->with('error', 'A senha informada não corresponde à senha atual!'); 
			}
 		}
		
		public function audit($id)
		{
			$user = User::findOrFail($id);
			$logs = $user->logs;
			$audit = array();
			$labels = array(
				'name' => 'Nome',
				'username' => 'Nome de Usuário',
				'email' => 'E-mail',
				'company_id' => 'Empresa',
				'role' => 'Perfil'
			);
			if ($logs)
			foreach($logs as $log)
			{
				foreach($log->new_value as $key => $value)
				{
					switch ($key){
						case 'role':
							$audit[] = array(
								'label' => $labels[$key],
								'old' => testVal($log->old_value, $key) ? fieldValue("all_roles", $log->old_value[$key]) : '',
								'new' => testVal($log->old_value, $key) ? fieldValue("all_roles", $log->new_value[$key]) : '',
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
			$grid->add('user', 'Alterado por');
			$grid->add('date', 'Data/Hora da Alteração');
			$grid->paginate(10);
			return view('layouts.audit', compact('grid'));
		}

}