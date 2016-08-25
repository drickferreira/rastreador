<?php namespace Modules\CommandsResponse\Http\Controllers;

use Pingpong\Modules\Routing\Controller;

class CommandsResponseController extends Controller {
	
	public function index()
	{
		return view('commandsresponse::index');
	}
	
}