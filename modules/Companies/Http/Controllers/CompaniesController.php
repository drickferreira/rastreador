<?php namespace Modules\Companies\Http\Controllers;

use Pingpong\Modules\Routing\Controller;

class CompaniesController extends Controller {
	
	public function index()
	{
		return view('companies::index');
	}
	
}