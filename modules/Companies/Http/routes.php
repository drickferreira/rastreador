<?php

Route::group(['prefix' => 'companies', 'namespace' => 'Modules\Companies\Http\Controllers'], function()
{
	Route::get('/', 'CompaniesController@index');
});