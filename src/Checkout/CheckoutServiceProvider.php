<?php

namespace TicketMiller\Checkout;

use Illuminate\Support\ServiceProvider;
use TicketMiller\Checkout\Providers\PaystackCheckoutService;

class CheckoutServiceProvider extends ServiceProvider
{
    private $services = [
        'paystack' => PaystackCheckoutService::class,
//        'rave' => RaveCheckoutService::class
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $service = $this->getService();
        $this->app->singleton(ICheckoutService::class, $service);
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
    private function getService(): string
    {
        $id = env('CHECKOUT_SERVICE_PROVIDER', 'paystack');
        return $this->services[$id];
    }
}
