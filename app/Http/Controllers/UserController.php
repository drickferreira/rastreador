<?php 

namespace App\Http\Controllers;

use Auth;
use Hash;
use App\User;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Password;
use Modules\Companies\Entities\Company;
use Illuminate\Http\Request;

class UserController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
			if (Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() ) {
				if (Auth::user()->isSuperAdmin()) {
					$grid = \DataGrid::source(User::with('Company'));
					$grid->label('Usuários');
					$grid->attributes(array("class"=>"table table-striped"));
					$grid->add('username','Usuário', true);
					$grid->add('name','Nome', true);
					$grid->add('Company.name','Empresa', 'company_id');
					$grid->add('{{ fieldValue("roles", $role) }}','Perfil', 'role');
					$grid->edit('user/edit', 'Ações','show|modify|delete');
					$grid->link('user/edit',"Novo Usuário", "TR");
				} else {
					$grid = \DataGrid::source(User::with('Company')->where('company_id', Auth::user()->company_id));
					$grid->label('Usuários');
					$grid->attributes(array("class"=>"table table-striped"));
					$grid->add('username','Usuário', true);
					$grid->add('name','Nome', true);
					$grid->add('role','Perfil');
					$grid->row(function ($row) {
						$role = $row->cells[2]->value;
						$classes = array( 10 => 'primary', 20 => 'success', 30 => 'default');
						if ($role == 40) {
							$row->cells[2]->value = '<a class="btn btn-danger btn-xs" title="Veículos" href="/user/vehicles/'.$row->data->id.'">'.fieldValue('company_roles', $row->cells[2]->value).' <i class="fa fa-car"></i></a>';
						} else {
							$row->cells[2]->value = '<button class="btn btn-'.$classes[$role].' btn-xs">'.fieldValue('company_roles', $row->cells[2]->value).'</button>';
						}
					});
					$grid->edit('user/edit', 'Ações','show|modify|delete');
					$grid->link('user/edit',"Novo Usuário", "TR");
				}
				$grid->orderBy('name','asc');
				return view('user.index', compact('grid'));
			} else {
				return redirect()->back()->with('error', 'Você não tem permissão para acessar esse módulo!');
			}
    }

    public function edit()
    {
			if (Auth::user()->isSuperAdmin() || Auth::user()->isAdmin()) {
				$form = \DataEdit::source(new User);
				$form->link("user","Usuários", "TR")->back();
				$form->text('username','Usuário')->rule('required|max:20')->unique();
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
			$form->tags('Vehicles.plate', 'Veículos');
			return $form->view('user.vehicles', compact('form'));
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
}