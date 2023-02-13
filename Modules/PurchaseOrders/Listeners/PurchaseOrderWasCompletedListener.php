<?php

namespace Modules\PurchaseOrders\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\BakeryManager\Entities\Rawmaterial;
use Modules\BakeryManager\Entities\Rawmaterialbatch;
use Modules\PurchaseOrders\Entities\PurchaseOrder;
use Modules\PurchaseOrders\Events\PurchaseOrderWasCompletedEvent;

class PurchaseOrderWasCompletedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(PurchaseOrderWasCompletedEvent $event)
    {

        if($event->purchase->type === Rawmaterial::class)
        {
            foreach ($event->purchase->purchase_order_items as $item) {

                \DB::transaction(function() use(&$event,&$item){
                    $item->batch_id = Rawmaterialbatch::create(
                        [
                            'received_date' => $event->purchase->date_completed,
                            'expiry_date' => $item->expiry_date,
                             $event->purchase->branch->quantity_column => $item->qty,
                            'supplier_id' => $event->purchase->supplier_id,
                            'rawmaterial_id' => $item->purchase_id
                        ]
                    )->id;

                    $item->update();

                    $item->purchase->cost_price = $item->cost_price;
                    $item->purchase->updateAvailableQuantity();

                });
            }
        }

    }
}
