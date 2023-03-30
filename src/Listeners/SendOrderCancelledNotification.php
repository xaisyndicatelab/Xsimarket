<?php

namespace Xsimarket\Listeners;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Xsimarket\Events\OrderCancelled;
use Xsimarket\Notifications\OrderCancelledNotification;

class SendOrderCancelledNotification implements ShouldQueue
{

    /**
     * Handle the event.
     *
     * @param OrderCancelled $event
     * @return void
     */
    public function handle(OrderCancelled $event)
    {
        foreach ($event->order->children as $key => $child_order) {
            $vendor_id = $child_order->shop->owner_id;
            $vendor = User::findOrFail($vendor_id);
            $vendor->notify(new OrderCancelledNotification($event->order));
        }
        $customer = $event->order->customer;
        if (isset($customer)) {
            $customer->notify(new OrderCancelledNotification($event->order));
        }
    }
}
