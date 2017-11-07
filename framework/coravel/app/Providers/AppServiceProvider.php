<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
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
