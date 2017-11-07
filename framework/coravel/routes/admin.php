<?php

Route::group(['namespace' => 'App\Http\Controllers'], function() {
    // Route::group(['middleware' => ['App\Http\Middleware\User\RedirectIfNotAuthenticated','App\Http\Middleware\TrimStrings']], function() {

	Route::get('category/get','Admin\CategoryController@getMenu');
	Route::put('category/create','Admin\CategoryController@store');
	Route::put('category/update','Admin\CategoryController@update');
	Route::put('category/arrange','Admin\CategoryController@arrange');
	Route::post('product/update','Admin\ProductController@update');
});


