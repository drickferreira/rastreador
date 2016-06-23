<?php

Route::group(['prefix' => 'vehicles', 'namespace' => 'Modules\Vehicles\Http\Controllers', 'middleware' => 'auth'], function()
{
	Route::get('/', 'VehiclesController@index');
	Route::any('edit', 'VehiclesController@edit');
	Route::get('audit/{id}', 'VehiclesController@audit');
});