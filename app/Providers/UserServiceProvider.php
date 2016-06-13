<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\PasswordController;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        User::created(function ($user) {
					$control = new PasswordController;
					$request = Request::capture();
					view()->composer('emails.password', function($view) {
							$view->with(['new_user'  => true]);
				 	});
					$result = $control->postEmail($request);
				});
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
