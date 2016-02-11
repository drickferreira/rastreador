<?php namespace Modules\Devices\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Modules\Devices\Entities\Device;
use Illuminate\Http\Request;
use Storage;

class DevicesController extends Controller {
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$devices = Device::latest()->get();
		return view('devices::index', compact('devices'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$disk = Storage::disk('icons');
		$icons = $disk->allFiles();
		$arr_icons = [];
		foreach ($icons as $icon){
			$arr_icons[asset('assets/icons/'.$icon)] = str_replace('.png', '', $icon);
		}
		return view('devices::create')->with('icons', $arr_icons);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, ['name' => 'required', 'model' => 'required', 'serial' => 'unique:devices']); 
		Device::create($request->all());
		return redirect('devices');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$device = Device::findOrFail($id);
		return view('devices::show', compact('device'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$disk = Storage::disk('icons');
		$icons = $disk->allFiles();
		$arr_icons = [];
		foreach ($icons as $icon){
			$arr_icons[] = asset('assets/icons/'.$icon);
		}
		$device = Device::findOrFail($id);
		return view('devices::edit', compact('device'))->with('icons', $arr_icons);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$this->validate($request, array( 
			'name' => 'required', 
			'model' => 'required', 
			'serial' => 'unique:devices,serial,'.$id
			)
		); 
		$device = Device::findOrFail($id);
		$device->update($request->all());
		return redirect('devices');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{

		Device::destroy($id);
	}

	
}