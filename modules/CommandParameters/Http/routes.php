<?php

Route::group(['prefix' => 'commandparameters', 'namespace' => 'Modules\CommandParameters\Http\Controllers'], function()
{
	Route::get('/', 'CommandParametersController@index');
});