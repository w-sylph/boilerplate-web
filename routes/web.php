<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::namespace('Auth')->group(function() {

	/* Guest Routes */
	Route::middleware('guest:web')->group(function() {

        Route::get('login', 'LoginController@showLoginForm')->name('login');
        Route::post('login', 'LoginController@login');

        Route::get('reset-password/{token}/{email}', 'ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('reset-password/change', 'ResetPasswordController@reset')->name('password.change');

        Route::get('forgot-password', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('forgot-password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');

        Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
        Route::post('register', 'RegisterController@register');

        /* Socialite Login */
        Route::get('socialite/{provider}/login', 'SocialiteLoginController@login')->name('socialite.login');
		Route::get('socialite/{provider}/callback', 'SocialiteLoginController@callback')->name('socialite.callback');

		/* Facebook Login */
		Route::get('socialite/facebook/login', 'SocialiteLoginController@login')->name('facebook.login');
		Route::get('socialite/facebook/callback', 'SocialiteLoginController@callback')->name('facebook.callback');

	});

    Route::get('email/verify/{id}', 'VerificationController@verify')->name('verification.verify');

});

/* User Dashboard Routes */
Route::prefix('dashboard')->middleware('auth:web')->group(function() {

	Route::namespace('Auth')->group(function() {

		Route::get('logout', 'LoginController@logout')->name('logout');

        Route::get('email/reset', 'VerificationController@resend')->name('verification.resend');
        Route::get('email/verify', 'VerificationController@show')->name('verification.notice');

	});

	Route::middleware('verified:web.verification.notice')->group(function() {

		Route::get('', 'DashboardController@index')->name('dashboard');

		/**
         * @Count Fetch Controller
         */
        Route::post('count/profile', 'CountFetchController@fetchProfileCount')->name('counts.fetch.profile');
        Route::post('count/notifications', 'CountFetchController@fetchNotificationCount')->name('counts.fetch.notifications');

		Route::namespace('Profiles')->group(function() {

            /**
             * @Profiles
             */
            Route::get('profile', 'ProfileController@show')->name('profiles.show');
            Route::post('profile/update', 'ProfileController@update')->name('profiles.update');

            Route::get('profile/change-password', 'ProfileController@showPassword')->name('profiles.change-password');
            Route::post('profile/change-password', 'ProfileController@changePassword')->name('profiles.change-password');

            Route::post('profile/fetch', 'ProfileController@fetch')->name('profiles.fetch');

        });

		Route::namespace('Notifications')->group(function() {

            Route::get('notifications', 'NotificationController@index')->name('notifications.index');
            Route::post('notifications/all/mark-as-read', 'NotificationController@readAll')->name('notifications.read-all');
            Route::post('notifications/{id}/read', 'NotificationController@read')->name('notifications.read');
            Route::post('notifications/{id}/unread', 'NotificationController@unread')->name('notifications.unread');

            Route::post('notifications-fetch', 'NotificationFetchController@fetch')->name('notifications.fetch');
            Route::post('notifications-fetch?read=1', 'NotificationFetchController@fetch')->name('notifications.fetch-read');
            Route::post('notifications-fetch?unread=1', 'NotificationFetchController@fetch')->name('notifications.fetch-unread');

        });

	});

});

