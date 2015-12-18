<?php

Route::group(['namespace' => 'Modules\Devices\Http\Controllers'], function()
{
	Route::resource('devices', 'DevicesController');
});