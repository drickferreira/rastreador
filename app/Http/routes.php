<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Authentication routes...

	Route::get('auth/login', 'Auth\AuthController@getLogin');
	Route::post('auth/login', 'Auth\AuthController@postLogin');
	Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Password reset link request routes...
	Route::get('password/email', 'Auth\PasswordController@getEmail');
	Route::post('password/email', 'Auth\PasswordController@postEmail');
	
	Route::group(['middleware' => 'auth'], function () {
	
			Route::get('/', 'DashBoardController@index');
			
			Route::group(['prefix' => 'user'], function()
			{
				Route::get('/', 'UserController@index');
				Route::any('edit', 'UserController@edit');
				Route::any('vehicles/{id}', 'UserController@vehicles');
				Route::get('reset', 'UserController@reset');
				Route::get('password', 'UserController@getpassword');
				Route::post('password', 'UserController@setpassword');
				Route::get('audit/{id}', 'UserController@audit');
				Route::get('autocomplete', 'UserController@getAutocomplete');
				Route::get('access/{id}', 'UserController@access');
			});
			
			Route::get('notReporting', 'ReportsController@NotReporting');
			Route::get('installByDay', 'ReportsController@installByDay');
			Route::get('locked', 'ReportsController@travados');
	});
	
	// Password reset routes...
	Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
	Route::post('password/reset', 'Auth\PasswordController@postReset');
	
	
	Route::group(array('prefix' => 'api'), function()
	{
		Route::get('/', 'ApiController@index');
		Route::get('position/{placa}', 'ApiController@getPosition');
	});
