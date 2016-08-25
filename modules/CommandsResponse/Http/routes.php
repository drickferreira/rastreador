<?php

Route::group(['prefix' => 'commandsresponse', 'namespace' => 'Modules\CommandsResponse\Http\Controllers'], function()
{
	Route::get('/', 'CommandsResponseController@index');
});