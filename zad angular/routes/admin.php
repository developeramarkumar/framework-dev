<?php
Route::get('/', 'Auth\LoginController@index')->name('admin.home');
Route::get('login', 'Auth\LoginController@showLoginForm')->name('admin.login.form');
Route::post('login', 'Auth\LoginController@login')->name('admin.login.post');
Route::get('logout', 'Auth\LoginController@logout')->name('admin.logout.get');
Route::group(['middleware' => 'admin'], function() {
    // Route::get('dashboard', 'DashboardController@index')->name('admin.dashboard');
    Route::get('pages/{page}', 'PageController@index')->name('admin.storage.page');
    Route::get('{path}', function() {
        return view('admin.layout.base');
    })->where('path','.+');
    
});
