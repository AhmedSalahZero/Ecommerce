<?php

namespace App\Providers;

use App\cart\Cart;
use App\cart\Payments\Gateway;
use App\cart\Payments\Gateways\StripeGateway;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Stripe\Stripe;


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

       $this->app->singleton(Gateway::class , function(){
          return new StripeGateway();
       });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Stripe::setApiKey(config('services.stripe.secret'));

    }
}
