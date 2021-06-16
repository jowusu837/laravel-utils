<?php


namespace TicketMiller\Checkout;


use Illuminate\Http\Request;

interface ICheckoutDriver
{
    public function initiate(Invoiceable $invoiceable): string;
    public function getProviderName(): string;
    public function handleCallback(Request $request, Invoiceable $invoiceable): bool;
    public function handleWebhook(Request $request): WebhookEvent;
}
