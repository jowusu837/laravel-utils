<?php


namespace TicketMiller\Facades;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;
use TicketMiller\Checkout\ICheckoutDriver;
use TicketMiller\Checkout\Invoiceable;

/**
 * Class CheckoutFacade
 * @package TicketMiller\Checkout
 *
 * @method static string initiate(Invoiceable $invoiceable)
 * @method static bool handleCallback(Request $request, Invoiceable $invoiceable)
 * @method static string getProviderName()
 */
class Checkout extends Facade
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