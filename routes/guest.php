<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
|
| Here is where you can register guest routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "guest" middleware group. Now create something great!
|
*/

/* Page Routes */
Route::namespace('Pages')->group(function() {

	Route::get('', 'PageController@showHome')->name('home');
	Route::get('terms-and-conditions', 'PageController@showTerms')->name('terms');
	Route::get('privacy-policy', 'PageController@showPrivacy')->name('privacy');

});