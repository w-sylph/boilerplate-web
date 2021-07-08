<?php

use App\Helpers\EnvHelper;
use Illuminate\Support\Facades\Route;

if (EnvHelper::isDev()) {
	
	Route::namespace('Developer')->prefix('developer')->name('developer.')->middleware('developer')->group(function() {

		Route::post('users/change-account', 'DeveloperController@changeAccount')->name('users.change-account');
		Route::post('users/fetch', 'DeveloperController@fetchUsers')->name('users.fetch');

	});

}