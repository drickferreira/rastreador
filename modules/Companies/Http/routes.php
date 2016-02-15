<?php
Route::group(['prefix' => 'companies', 'namespace' => 'Modules\Companies\Http\Controllers', 'middleware' => 'auth'], function()
{
	Route::get('/', 'CompaniesController@index');
	Route::any('edit', 'CompaniesController@edit');
});