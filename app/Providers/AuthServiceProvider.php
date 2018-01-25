<?php

namespace laravel\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'laravel\Thread' => 'laravel\Policies\ThreadPolicy',
        'laravel\Reply' => 'laravel\Policies\ReplyPolicy',
        'laravel\User' => 'laravel\Policies\UserPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Gate::before(function($user){
        //  return true;
        //});
    }
}
