<?php

Route::group(['prefix'=> 'positions', 'namespace' => 'Modules\Positions\Http\Controllers'], function()
{
	Route::get('/', 'PositionsController@index')->name('positions.index');
	Route::get('getAddress', 'PositionsController@getAddress')->name('positions.getAddress');
	Route::get('showMap/{id}', 'PositionsController@showMap')->name('positions.showMap');
	Route::get('showInfo/{id}', 'PositionsController@showInfo')->name('positions.showInfo');
	Route::get('showRoute', 'PositionsController@showRoute')->name('positions.showRoute');
});