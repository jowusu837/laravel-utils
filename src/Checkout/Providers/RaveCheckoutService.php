<?php


namespace TicketMiller\Checkout\Providers;


use App\Invoice;
use App\Lib\Rave\OnlineCheckout as RaveOnlineCheckout;
use App\RaveTransaction;
use Exception;
use Illuminate\Http\Request;
use Throwable;
use TicketMiller\Checkout\ICheckoutService;
use TicketMiller\Checkout\Invoiceable;

class RaveCheckoutService implements ICheckoutService
{
    /**
     * @param Invoiceable $invoiceable
     * @return string
     * @throws Throwable
     */
    public function initiate(Invoiceable $invoiceable): string
    {
        $transaction = RaveTransaction::createInstance($invoice->amount, $invoice->customer_email, $invoice->customer_name, $invoice->customer_phone);
        $transaction->invoice_id = $invoice->id;
        $transaction->saveOrFail();
        if ($url = RaveOnlineCheckout::initiate($transaction, $callbackUrl)) {
            return $url;
        }
        throw new Exception('Checkout failed!');
    }

    public function complete()
    {
        // TODO: Implement complete() method.
    }

    public function cancel()
    {
        // TODO: Implement cancel() method.
    }

    public function handleCallback(Request $request, Invoice $invoice)
    {
        // TODO: Implement callback() method.
    }

    public function getProviderName(): string
    {
        // TODO: Implement getProviderName() method.
    }
}