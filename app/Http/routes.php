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
	
	Route::group(['middleware' => 'auth'], function () {
	
			Route::get('/', 'DashBoardController@index');
			
			
			Route::group(['prefix' => 'user'], function()
			{
				Route::get('/', 'UserController@index');
				Route::any('edit', 'UserController@edit');
				Route::get('vehicles/{id}', 'UserController@vehicles');
				Route::get('reset', 'UserController@reset');
				Route::get('password', 'UserController@getpassword');
				Route::post('password', 'UserController@setpassword');
			});
			
			Route::get('notReporting', 'ReportsController@NotReporting');
	});
	
	Route::get('adm', function(){
		return view('auth.adm');
	});
	
	// Password reset routes...
	Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
	Route::post('password/reset', 'Auth\PasswordController@postReset');

