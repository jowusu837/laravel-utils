<?php

namespace TicketMiller\Checkout;

use Illuminate\Support\ServiceProvider;
use TicketMiller\Checkout\Drivers\PaystackCheckoutDriver;

class CheckoutServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

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
        $id = config('checkout.driver') ?? 'paystack';
        return $this->drivers[$id];
    }

    /**
     * Get the services provided by this provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [ICheckoutDriver::class];
    }
}
