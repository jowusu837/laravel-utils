<?php


namespace TicketMiller\Checkout;


use Illuminate\Http\Request;

interface ICheckoutDriver
{
    public function initiate(Invoiceable $invoiceable): string;
    public function handleCallback(Request $request, Invoiceable $invoiceable): bool;
    public function getProviderName(): string;
}