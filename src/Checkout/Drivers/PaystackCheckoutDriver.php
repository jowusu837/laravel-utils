<?php


namespace TicketMiller\Checkout\Drivers;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use TicketMiller\Checkout\ICheckoutDriver;
use TicketMiller\Checkout\Invoiceable;
use TicketMiller\Checkout\WebhookEvent;

class PaystackCheckoutDriver implements ICheckoutDriver
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $config;

    public function __construct()
    {
        $this->config = config('checkout.drivers.paystack');
        $this->client = new Client([
            'base_uri' => 'https://api.paystack.co',
            'timeout' => $this->config['timeout'],
            'headers' => [
                'Authorization' => 'Bearer ' . $this->config['secretKey'],
                'Cache-Control' => 'no-cache'
            ]
        ]);
    }

    /**
     * @param Invoiceable $invoiceable
     * @return string
     * @throws GuzzleException
     */
    public function initiate(Invoiceable $invoiceable): string
    {
        $response = $this->client->request('post', '/transaction/initialize', [
            'json' => [
                'email' => $invoiceable->getEmailAddress(),
                'amount' => $invoiceable->getAmount() * 100,
                'currency' => $invoiceable->getCurrency(),
                'callback_url' => $invoiceable->getCheckoutCallbackUrl(),
                'metadata' => json_encode($invoiceable->getCheckoutMeta())
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

    public function handleWebhook(Request $request): WebhookEvent
    {
        $signature = $request->header('x-paystack-signature');

        // only a post with paystack signature header gets our attention
        if (!$request->isMethod('post') || !$signature) {
            Log::debug("Webhook request must be post");
            return WebhookEvent::failed();
        }

        // Retrieve the request's body
        $payload = $request->all();

        $secretKey = $this->config['secretKey'];

        // validate event do all at once to avoid timing attack
        if ($signature !== hash_hmac('sha512', json_encode($payload), $secretKey)) {
            Log::debug("Invalid webhook signature!");
            return WebhookEvent::failed();
        }

        $event = WebhookEvent::successful();
        $event->metadata = $payload['data']['metadata'];

        return $event;
    }
}
