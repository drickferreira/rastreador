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

Route::get('/', 'DashBoardController@index');

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

Route::group(['prefix' => 'user', 'middleware' => 'auth'], function()
{
	Route::get('/', 'UserController@index');
	Route::any('edit', 'UserController@edit');
});


// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

Route::get('notReporting', 'ReportsController@NotReporting');


/*

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

Route::resource('maps','MapController');

Route::get('mail', function(){
	return view('emails.index');
});
*/
Route::get('mail/send', 'MapController@sendmail');

Route::get('sendemail', function () {

    $data = array(
        'name' => "Learning Laravel",
    );

    Mail::send('emails.test', $data, function ($message) {

        $message->from('drickferreira@afinet.com.br', 'Learning Laravel');

        $message->to('drickferreira@afinet.com.br')->subject('Learning Laravel test email');

    });

    return "Your email has been sent successfully";

});
