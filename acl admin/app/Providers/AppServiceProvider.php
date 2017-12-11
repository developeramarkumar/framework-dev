<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Requests\CreateRequest;
use App\Http\Requests\ReadRequest;
use App\Http\Requests\UpdateRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Contracts\Auth\Access\Gate;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
//         $request = new App\Http\Requests\UpdateRequest;
// $request->setContainer(app());
// $request->setRedirector(app('Illuminate\Routing\Redirector'));
// $request->validate();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->instance('create', new CreateRequest);
        $this->app->instance('read', new ReadRequest);
        $this->app->instance('update', new UpdateRequest);
        $this->app->instance('delete', new DeleteRequest);
        // $app['request']->setSession($app['session']->driver('array'));
        // $this->app['request']->setSession($this->app['session']->driver('array'));
        // $this->app->make('Illuminate\Contracts\Http\Kernel')->pushMiddleware('Illuminate\Session\Middleware\StartSession');


        // Gate::define('access');
        // $this->app->instance('Illuminate\Contracts\Auth\Access\Gate', \Illuminate\Contracts\Auth\Access\Gate::class);

    //      $this->app->resolving(function (UpdateRequest $request, $app) {
    //     // [.... custom functions to call on request here ....]
    // });
//         $request = new \App\Http\Requests\UpdateRequest;
// $request->setContainer(app());
// $request->setRedirector(app('Illuminate\Routing\Redirector'));
// $request->validate();
        // $this->app->singleton('filesystem','Illuminate\Filesystem\FilesystemServiceProvider');
//       $app->singleton(
//     Illuminate\Contracts\Filesystem\Factory::class,
//     function ($app) {
//         return new Illuminate\Filesystem\FilesystemManager($app);
//     }
// );

//         $this->app->alias('filesystem', 'Illuminate\Filesystem\FilesystemManager');
        // $this->app->register('Illuminate\Events\ValidationServiceProvider');
    }
    // public function provides()
    // {
    //     return [Connection::class];
    // }
}
