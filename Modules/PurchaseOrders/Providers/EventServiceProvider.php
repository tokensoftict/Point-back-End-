<?php

namespace Modules\PurchaseOrders\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\PurchaseOrders\Events\PurchaseOrderWasCompletedEvent;
use Modules\PurchaseOrders\Listeners\PurchaseOrderWasCompletedListener;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        PurchaseOrderWasCompletedEvent::class => [
            PurchaseOrderWasCompletedListener::class
        ]
    ];

}
