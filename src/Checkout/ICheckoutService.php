<?php


namespace TicketMiller\Checkout;


use Illuminate\Support\Facades\Request;

interface ICheckoutService
{
    public function initiate(Invoiceable $invoiceable): string;
    public function handleCallback(Request $request, Invoiceable $invoiceable): bool;
    public function getProviderName(): string;
}