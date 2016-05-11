<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use App\User;
use Modules\Companies\Entities\Company;

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
					$grid->attributes(array("class"=>"table table-striped"));
					$grid->add('username','Usuário', true);
					$grid->add('name','Nome', true);
					$grid->add('Company.name','Empresa', 'company_id');
					$grid->edit('user/edit', 'Ações','show|modify|delete');
					$grid->link('user/edit',"Novo Usuário", "TR");
				} else {
					$grid = \DataGrid::source(User::with('Company')->where('company_id', Auth::user()->company_id));
					$grid->attributes(array("class"=>"table table-striped"));
					$grid->add('username','Usuário', true);
					$grid->add('name','Nome', true);
					$grid->edit('user/edit', 'Ações','show|modify|delete');
					$grid->link('user/create',"Novo Usuário", "TR");
				}
				$grid->orderBy('name','asc');
				return view('user.index', compact('grid'));
			} else {
				return view('errors.503');
			}
    }

    public function create()
    {
			if (Auth::user()->isSuperAdmin()) {
				$form = \DataEdit::source(new User);
				$form->link("user","Voltar", "TR")->back();
				$form->text('name','Nome');
				$options = ['' => ''] + Company::lists('name', 'id')->all();
				$form->select('company_id','Empresa')->options($options);
				$form->text('email','Email')->rule('email');
				$form->select('role','Perfil')->options(config("dropdown.roles"));
				return $form->view('user.create', compact('form'));
			} else {
				return view('errors.503');
			}
    }

 
    public function edit()
    {
			if (Auth::user()->isSuperAdmin()) {
				$form = \DataEdit::source(new User);
				$form->link("user","Voltar", "TR")->back();
				$form->text('username','Usuário')->rule('required|min:5');
				$form->text('name','Nome');
				$options = ['' => ''] + Company::lists('name', 'id')->all();
				$form->select('company_id','Empresa')->options($options);
				$form->text('email','Email')->rule('email');
				$form->select('role','Perfil')->options(config("dropdown.roles"));
				return $form->view('user.edit', compact('form'));
			} else {
				return view('errors.503');
			}
    }
}