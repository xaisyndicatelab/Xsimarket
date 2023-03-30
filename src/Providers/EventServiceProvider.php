<?php

namespace Xsimarket\Providers;

use App\Events\QuestionAnswered;
use App\Events\RefundApproved;
use App\Events\ReviewCreated;
use App\Listeners\RatingRemoved;
use App\Listeners\SendQuestionAnsweredNotification;
use App\Listeners\SendReviewNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Xsimarket\Events\OrderCancelled;
use Xsimarket\Events\OrderCreated;
use Xsimarket\Events\OrderProcessed;
use Xsimarket\Events\OrderReceived;
use Xsimarket\Events\PaymentFailed;
use Xsimarket\Events\PaymentMethods;
use Xsimarket\Events\PaymentSuccess;
use Xsimarket\Listeners\CheckAndSetDefaultCard;
use Xsimarket\Listeners\ProductInventoryDecrement;
use Xsimarket\Listeners\ProductInventoryRestore;
use Xsimarket\Listeners\SendOrderCreationNotification;
use Xsimarket\Listeners\SendOrderCancelledNotification;
use Xsimarket\Listeners\SendOrderReceivedNotification;
use Xsimarket\Listeners\SendPaymentFailedNotification;
use Xsimarket\Listeners\SendPaymentSuccessNotification;

class EventServiceProvider extends ServiceProvider
{

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        QuestionAnswered::class => [
            SendQuestionAnsweredNotification::class
        ],
        ReviewCreated::class => [
            SendReviewNotification::class
        ],
        OrderCreated::class => [
            SendOrderCreationNotification::class,
        ],
        OrderReceived::class => [
            SendOrderReceivedNotification::class
        ],
        OrderProcessed::class => [
            ProductInventoryDecrement::class,
        ],
        OrderCancelled::class => [
            ProductInventoryRestore::class,
            SendOrderCancelledNotification::class
        ],
        RefundApproved::class => [
            RatingRemoved::class
        ],
        PaymentSuccess::class => [
            SendPaymentSuccessNotification::class
        ],
        PaymentFailed::class => [
            SendPaymentFailedNotification::class
        ],
        PaymentMethods::class => [
            CheckAndSetDefaultCard::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
