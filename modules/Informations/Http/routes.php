<?php

Route::group(['prefix' => 'informations', 'namespace' => 'Modules\Informations\Http\Controllers'], function()
{
	Route::get('/', 'InformationsController@index');
});