<?php


namespace TicketMiller\Checkout\Providers;


use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use TicketMiller\Checkout\ICheckoutService;
use TicketMiller\Checkout\Invoiceable;

class PaystackCheckoutService implements ICheckoutService
{
    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.paystack.co',
            'timeout' => config('checkout.paystack.timeout'),
            'headers' => [
                'Authorization' => 'Bearer ' . config('checkout.paystack.secretKey'),
                'Cache-Control' => 'no-cache'
            ]
        ]);
    }

    public function initiate(Invoiceable $invoiceable): string
    {
        $response = $this->client->request('post', '/transaction/initialize', [
            'json' => [
                'email' => $invoiceable->getEmailAddress(),
                'amount' => $invoiceable->getAmount() * 100,
                'currency' => $invoiceable->getCurrency(),
                'callback_url' => $invoiceable->getCheckoutCallbackUrl()
            ]
        ]);

        $body = json_decode((string)$response->getBody(), true);

        if (!$invoiceable->saveCheckoutReference($body['data']['reference'])) {
            Log::critical('Could not save checkout reference!', compact('invoiceable', 'body'));
        }
        return $body['data']['authorization_url'];
    }

    public function handleCallback(Request $request, Invoiceable $invoiceable): bool
    {
        $reference = $request->query('reference', $invoiceable->getCheckoutReference());
        $response = $this->client->get("/transaction/verify/{$reference}");
        $body = json_decode((string)$response->getBody(), true);
        return $body['data']['status'] == 'success' && $body['data']['amount'] == $invoiceable->getAmount() * 100 && $body['data']['currency'] == $invoiceable->getCurrency();
    }

    public function getProviderName(): string
    {
        return 'Paystack';
    }
}