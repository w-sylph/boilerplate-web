<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Common Routes
|--------------------------------------------------------------------------
|
| Here is where you can register common routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "common" middleware group. Now create something great!
|
*/

/**
 * @Testing Area
 */

Route::get('sandbox', 'SandboxController@index')->name('sandbox.index');
Route::post('api/v1/fetch-locations', 'LocationController@fetch')->name('locations.fetch');

Route::post('api/v1/file-upload', 'DropzoneController@store')->name('dropzone.store');

/**
 * @Admin Routes
 */

Route::middleware('auth:admin')->group(function() {

	/**
	 * Ckeditor Image Upload
	 */
    Route::post('ckeditor/upload', 'CkeditorController@upload');

    /**
     * Google Fetch Address Position
     */
    Route::post('google/position/fetch', 'GoogleAPIController@fetchAddressPosition')->name('google.fetch.address-position');

    /**
     * Sortable
     */
    Route::post('sortable', 'SortableController@sort')->name('sortable');
    Route::post('sortable?type=file', 'SortableController@sort')->name('sort.file');
    Route::post('sortable?type=sample', 'SortableController@sort')->name('sort.sample');
    Route::post('sortable?type=article', 'SortableController@sort')->name('sort.article');
    Route::post('sortable?type=carousel', 'SortableController@sort')->name('sort.carousel');
    Route::post('sortable?type=collection', 'SortableController@sort')->name('sort.collection');
    Route::post('sortable?type=product', 'SortableController@sort')->name('sort.product');
    Route::post('sortable?type=product-category', 'SortableController@sort')->name('sort.product-category');
    Route::post('sortable?type=option', 'SortableController@sort')->name('sort.product-option');
    Route::post('sortable?type=option-value', 'SortableController@sort')->name('sort.product-option-value');
    Route::post('sortable?type=payment-type', 'SortableController@sort')->name('sort.payment-type');
    Route::post('sortable?type=shipping-rate', 'SortableController@sort')->name('sort.shipping-rate');

    /**
     * Images
     */
    Route::post('file/upload', 'FileRecordController@upload')->name('file.upload');
    Route::post('file/remove/{id}', 'FileRecordController@remove')->name('file.remove');
    Route::post('file/remove-temp', 'FileRecordController@removeTemp')->name('file.remove-temp');

});