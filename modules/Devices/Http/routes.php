<?php

Route::group(['prefix'=> 'devices', 'namespace' => 'Modules\Devices\Http\Controllers', 'middleware' => 'auth'], function()
{
	Route::get('/', 'DevicesController@index');
	Route::any('edit', 'DevicesController@edit');
	Route::get('vehicle/{id}', 'DevicesController@getVehicle');
	Route::post('vehicle/{id}', 'DevicesController@postVehicle');
});