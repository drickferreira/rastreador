<?php

Route::group(['namespace' => 'Modules\Devices\Http\Controllers', 'middleware' => 'auth'], function()
{
	Route::resource('devices', 'DevicesController');
});