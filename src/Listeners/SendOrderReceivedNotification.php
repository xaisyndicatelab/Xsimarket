<?php

namespace Xsimarket\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Xsimarket\Events\OrderReceived;
use Xsimarket\Notifications\NewOrderReceived;

class SendOrderReceivedNotification implements ShouldQueue
{

    /**
     * Handle the event.
     *
     * @param OrderReceived $event
     * @return void
     */
    public function handle(OrderReceived $event)
    {
        $vendor = $event->order->shop->owner;
        $vendor->notify(new NewOrderReceived($event->order));
    }
}
