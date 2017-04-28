<?php namespace Modules\Informations\Http\Controllers;

use Pingpong\Modules\Routing\Controller;

class InformationsController extends Controller {
	
	public function index()
	{
		return view('informations::index');
	}
	
}