<?php

namespace TicketMiller\Checkout;

use Illuminate\Support\ServiceProvider;
use TicketMiller\Checkout\Drivers\PaystackCheckoutDriver;

class CheckoutServiceProvider extends ServiceProvider
{
    private $drivers = [
        'paystack' => PaystackCheckoutDriver::class,
//        'rave' => RaveCheckoutService::class
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $service = $this->getDriver();
        $this->app->singleton(ICheckoutDriver::class, $service);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/checkout.config.php' => config_path('checkout.php')
        ]);
    }

    /**
     * @return string
     */
    private function getDriver(): string
    {
        $id = config('checkout.driver');
        return $this->drivers[$id];
    }
}
