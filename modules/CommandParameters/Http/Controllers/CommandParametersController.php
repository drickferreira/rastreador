<?php namespace Modules\CommandParameters\Http\Controllers;

use Pingpong\Modules\Routing\Controller;

class CommandParametersController extends Controller {
	
	public function index()
	{
		return view('commandparameters::index');
	}
	
}