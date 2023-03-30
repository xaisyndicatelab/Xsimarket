<?php

namespace Xsimarket\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Xsimarket\Events\OrderCreated;
use Xsimarket\Notifications\OrderPlacedSuccessfully;

class SendOrderCreationNotification implements ShouldQueue
{

    /**
     * Handle the event.
     *
     * @param OrderCreated $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        $customer = $event->order->customer;
        if (isset($customer)) {
            $customer->notify(new OrderPlacedSuccessfully($event->order));
        }
    }
}
