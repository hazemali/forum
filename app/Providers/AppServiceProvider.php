<?php

namespace App\Providers;

use App\Channel;
use Barryvdh\Debugbar\ServiceProvider as DebugerServiceProvider;
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

        \View::composer('*', function ($view) {

            $channels = \Cache::rememberForEver('channels',function(){
                return Channel::all();
            });

            $view->with('channels', $channels);

        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if($this->app->isLocal()){

            $this->app->register(DebugerServiceProvider::class);
        }
    }
}
