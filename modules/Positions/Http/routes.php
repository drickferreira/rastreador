<?php

Route::group(array('prefix'=> 'positions', 'namespace' => 'Modules\Positions\Http\Controllers', 'middleware' => 'auth'), function()
{
	Route::get('/', 'PositionsController@index')->name('positions.index');
	Route::get('getAddress', 'PositionsController@getAddress')->name('positions.getAddress');
	Route::get('showMap/{id}', 'PositionsController@showMap')->name('positions.showMap');
	Route::post('showAllMap', 'PositionsController@showAllMap')->name('positions.showAllMap');
	Route::get('showInfo/{id}', 'PositionsController@showInfo')->name('positions.showInfo');
	Route::post('showRoute', 'PositionsController@showRoute')->name('positions.showRoute');
	Route::post('updatePositions', 'PositionsController@updatePositions');
	Route::get('showLast/{id}', 'PositionsController@showLast')->name('positions.showLast');
	Route::get('history/{id}', 'PositionsController@searchHistory')->name('positions.history');
});