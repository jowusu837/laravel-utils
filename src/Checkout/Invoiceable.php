<?php


namespace TicketMiller\Checkout;


interface Invoiceable
{
    public function getEmailAddress(): string;
    public function getAmount(): string;
    public function getCurrency(): string;
    public function getCheckoutCallbackUrl(): string;
    public function saveCheckoutReference($reference): bool;
    public function getCheckoutReference(): string;
    public function getCheckoutMeta(): array;
}
