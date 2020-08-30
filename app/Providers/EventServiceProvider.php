<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Orders\OrderCreated' => [
            'App\Listener\Orders\ProcessPayment',
            'App\Listener\Orders\EmptyCart'
        ],
        'App\Events\Orders\OrderPaymentFailed' => [
            'App\Listener\Orders\MarkOrderPaymentFailed',
        ],
        'App\Events\Orders\OrderPaid' => [
            'App\Listener\Orders\CreateTransaction',
            'App\Listener\Orders\MarkOrderProcessing',
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
