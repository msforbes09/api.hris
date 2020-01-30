<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Laravel\Passport\RouteRegistrar;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\Resource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Resource::withoutWrapping();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // DEFAULT STRING LENGTH
        Schema::defaultStringLength(191);

        // PASSPORT
        Passport::routes(function (RouteRegistrar $router)
            {
                $router->forAccessTokens();
            });
        Passport::tokensExpireIn(now()->addDays(1));
    }
}
