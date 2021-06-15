<?php


namespace TicketMiller\Checkout;


class WebhookEvent
{
    /**
     * @var bool
     */
    public $is_successful;

    /**
     * @var array
     */
    public $metadata = [];

    public static function failed(): WebhookEvent
    {
        $event = new self();
        $event->is_successful = false;
        return $event;
    }

    public static function successful(): WebhookEvent
    {
        $event = new self();
        $event->is_successful = true;
        return $event;
    }
}
