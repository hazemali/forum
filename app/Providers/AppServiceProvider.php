<?php

namespace laravel\Providers;

use laravel\Channel;
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

        \Validator::extend('spamFree' , 'laravel\Rules\SpamFree@passes');


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
