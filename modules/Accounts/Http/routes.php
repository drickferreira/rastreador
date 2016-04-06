<?php
Route::group(['prefix' => 'accounts', 'namespace' => 'Modules\Accounts\Http\Controllers', 'middleware' => 'auth'], function()
{
	Route::get('/', 'AccountsController@index');
	Route::any('edit', 'AccountsController@edit');
});