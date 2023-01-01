<?php

namespace Modules\PurchaseOrders\Events;

use Illuminate\Queue\SerializesModels;
use Modules\PurchaseOrders\Entities\PurchaseOrder;

class PurchaseOrderWasCompletedEvent
{
    use SerializesModels;

    public PurchaseOrder $purchase;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PurchaseOrder $purchaseOrder)
    {
        $this->purchase = $purchaseOrder;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
