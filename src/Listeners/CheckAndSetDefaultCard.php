<?php

namespace Xsimarket\Listeners;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Xsimarket\Database\Models\PaymentMethod;
use Xsimarket\Events\PaymentMethods;

class CheckAndSetDefaultCard implements ShouldQueue
{

    protected function fetchAllPaymentMethods()
    {
        return PaymentMethod::all();
    }

    /**
     * Handle the event.
     *
     * @param PaymentMethods $event
     * @return void
     */
    public function handle(PaymentMethods $event)
    {
        $currentPaymentMethods = $event->payment_methods;
        $allPaymentMethods = $this->fetchAllPaymentMethods();

        if ($currentPaymentMethods->default_card) {
            foreach ($allPaymentMethods as $key => $paymentMethod) {
                if ($paymentMethod->method_key !== $currentPaymentMethods->method_key) {
                    $paymentMethod->default_card = false;
                    $paymentMethod->save();
                }
            }
        }
    }
}
