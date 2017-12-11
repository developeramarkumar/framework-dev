<?php 
include_once(__DIR__.'/../bootstrap/autoload.php');
Route::group(['namespace' => 'App\Http\Controllers\Admin','middleware' => ['\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull','App\Http\Middleware\TrimStrings']], function() {

    Route::group(['middleware' => 'App\Http\Middleware\Admin\RedirectIfAuthenticated'], function() {
        Route::get('/', function() {
       return redirect('login');
    });
    Route::get('/login',['uses'=>'Auth\LoginController@showLoginForm','as'=>'admin.login.form'] );
    Route::post('/login', ['uses'=>'Auth\LoginController@login','as'=>'admin.login.post']);

});
Route::group(['middleware' => 'App\Http\Middleware\Admin\RedirectIfNotAuthenticated'], function() {
    Route::get('dashboard',['uses'=>'DashboardController@index','as'=>'dashboard.index']);
    Route::get('logout', ['uses'=>'Auth\LoginController@logout','as'=>'admin.logout']);
   
    Route::group(['prefix' => 'database'], function() {
        Route::get('/', ['uses'=>'DatabaseController@index','as'=>'database.index']);
        Route::get('add/{table}', ['uses'=>'DatabaseController@create','as'=>'database.index']);
        Route::post('add', ['uses'=>'DatabaseController@store','as'=>'menu.store']);
    });
  
    Route::group(['prefix' => 'bread'], function() {
        Route::get('list', ['uses'=>'BreadController@index','as'=>'bread.index']);
        Route::get('create/{table}', ['uses'=>'BreadController@create','as'=>'bread.create']);
        Route::get('edit/{table}',  ['uses'=>'BreadController@edit','as'=>'bread.edit']);
        Route::post('store/{table}', ['uses'=>'BreadController@store','as'=>'bread.store']);
        Route::get('delete/{table}', ['uses'=>'BreadController@destroy','as'=>'bread.delete']);
        Route::put('update/{table}', ['uses'=>'BreadController@update','as'=>'bread.update']);
        
    });
    foreach (\App\Model\Menu::all() as $menu) {
         Route::resource($menu->slug, $menu->controller);
    }
  
    });

});
// dd($ioc['router']);
$response = $ioc['router']->dispatch(app('request'));
$response->send();
// app('session')->save();
 // $manager = new Illuminate\Session\Middleware\StartSession(app('session'));
 //    $response = $manager->handle($response,app('request'));
 //    $manager->terminate(); //This method prints my session file to the screen C:\laravel/app/storage/framework/sessions/996010fe0a54f84234ba5e3c420273cb1d79e4f8 
 //    $response->send();

// Core\Session::delete(['flash','error','old']);