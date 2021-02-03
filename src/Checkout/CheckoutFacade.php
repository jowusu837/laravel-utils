<?php


namespace TicketMiller\Checkout;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;

/**
 * Class CheckoutFacade
 * @package TicketMiller\Checkout
 *
 * @method static string initiate(Invoiceable $invoiceable)
 * @method static bool handleCallback(Request $request, Invoiceable $invoiceable)
 * @method static string getProviderName()
 */
class CheckoutFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return ICheckoutDriver::class;
    }
}