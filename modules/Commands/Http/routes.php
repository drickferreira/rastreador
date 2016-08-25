<?php

Route::group(['prefix' => 'commands', 'namespace' => 'Modules\Commands\Http\Controllers', 'middleware' => 'auth'], function()
{
	Route::get('/', 'CommandsController@index');
	Route::any('edit', 'CommandsController@edit');
	Route::get('send/{id}', 'CommandsController@getCommand');
	Route::get('arguments', 'CommandsController@getArguments');
	Route::post('arguments', 'CommandsController@postArguments');
	Route::get('mass', 'CommandsController@MassCommands');
	Route::get('massarguments', 'CommandsController@MassArguments');
	Route::get('massdevices', 'CommandsController@getMassDevices');
	Route::post('massdevices', 'CommandsController@postMassDevices');
	Route::post('devices', 'CommandsController@getDevicelist');
	Route::post('createmass', 'CommandsController@createMass');
});