<?php

namespace App\Providers;

use App\cart\Cart;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

       $this->app->singleton(Cart::class , function($app){
           $app->auth->user()->load(['cart.stock']);

           return new Cart(Request()->user());
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        //
    }
}
