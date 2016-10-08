<?php

Route::group(['prefix'=> 'devices', 'namespace' => 'Modules\Devices\Http\Controllers', 'middleware' => 'auth'], function()
{
	Route::get('/', 'DevicesController@index');
	Route::any('edit', 'DevicesController@edit');
	Route::get('vehicle/{id}', 'DevicesController@getVehicle');
	Route::post('vehicle/{id}', 'DevicesController@postVehicle');
	Route::get('test/{id}', 'DevicesController@test');
	Route::get('test', 'DevicesController@testSerial');
	Route::post('lastPosition', 'DevicesController@searchLastBySerial');
	Route::post('search', 'DevicesController@searchBySerial');
	Route::get('audit/{id}', 'DevicesController@audit');
	Route::get('active/{id}', 'DevicesController@active');
	Route::get('exchange', 'DevicesController@exchange');
	Route::post('exchange', 'DevicesController@postexchange');
	Route::post('search', 'DevicesController@search');
});