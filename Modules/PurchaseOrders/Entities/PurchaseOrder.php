<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\PurchaseOrders\Entities;


use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Auth\Entities\User;
use Modules\BakeryManager\Entities\Rawmaterial;
use Modules\BakeryManager\Entities\Rawmaterialbatch;
use Modules\PurchaseOrders\Events\PurchaseOrderWasCompletedEvent;
use Modules\PurchaseOrders\Http\Requests\PurchaseOrderRequest;
use Modules\Settings\Entities\Supplier;

/**
 * Class PurchaseOrder
 *
 * @property int $id
 * @property int|null $supplier_id
 * @property Carbon|null $date_created
 * @property Carbon|null $date_approved
 * @property Carbon|null $date_completed
 * @property string|null $type
 * @property float $total
 * @property int $status_id
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $approved_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property User|null $user
 * @property Supplier|null $supplier
 * @property Collection|PurchaseOrderItem[] $purchase_order_items
 *
 * @package App\Models
 */
class PurchaseOrder extends Model
{
    protected $table = 'purchase_orders';

    protected $with = ['supplier'];

    protected $casts = [
        'supplier_id' => 'int',
        'total' => 'float',
        'status_id' => 'int',
        'created_by' => 'int',
        'updated_by' => 'int',
        'approved_by' => 'int'
    ];

    protected $dates = [
        'date_created',
        'date_approved',
        'date_completed'
    ];

    protected $fillable = [
        'supplier_id',
        'date_created',
        'date_approved',
        'date_completed',
        'type',
        'total',
        'status_id',
        'created_by',
        'updated_by',
        'approved_by'
    ];

    public static array $tableColumn = [
        "No",
        "Supplier",
        "No_of_Items",
        "Total",
        "Date",
        "Status",
        "Created_By",
        "Action"
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function created_user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function approved_user()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function purchase_order_items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }


    public static function savePurchaseOrder(PurchaseOrderRequest $request, PurchaseOrder $purchase_order = NULL){

        $items = json_decode($request->get("items"),true);

        $total_purchase = 0;

        $purchase_order_items = [];

        foreach ($items as $item)
        {
            $total_purchase +=($item['quantity'] * $item['cost_price']);
            $purchase_order_items[] = new PurchaseOrderItem(
                [
                    "purchase_type" => Rawmaterial::class,
                    "purchase_id" => $item['id'],
                    "batch_type" => Rawmaterialbatch::class,
                    "batch_id" => NULL,
                    "expiry_date" => $item['expiry_date'],
                    "store" => "DEFAULT",
                    "qty" => $item['quantity'],
                    "cost_price" =>  $item['cost_price'],
                    "selling_price" => $item['cost_price'],
                    "added_by" => auth()->id()
                ]
            );
        }


        $data = [
            "supplier_id" => $request->get("supplier_id"),
            "date_created" => $request->get("date_created"),
            "date_approved" => $request->get("date_created"),
            "date_completed" => $request->get("complete_purchase") === "1" ? date("Y-m-d") : NULL,
            "type" => Rawmaterial::class,
            "total" => $total_purchase,
            "status_id" => $request->get("complete_purchase") === "1" ? Status::where("name","Complete")->first()->id :  Status::where("name","Draft")->first()->id,
            "created_by" =>  auth()->id(),
            "updated_by" =>  auth()->id(),
            "approved_by" =>  auth()->id()
        ];

        $po = NULL;

        if ($purchase_order != NULL)
        {
            \Arr::forget($data,["created_by"]);


            DB::transaction(function () use(&$purchase_order,&$data,&$purchase_order_items, &$po) {

                $purchase_order->purchase_order_items()->delete();

                $purchase_order->update($data);

                $purchase_order->purchase_order_items()->saveMany($purchase_order_items);

                $po = $purchase_order;
            });

        }
        else {

            DB::transaction(function() use(&$purchase_order,&$data,&$purchase_order_items, &$po){

                $po =  PurchaseOrder::create($data);

                $po->purchase_order_items()->saveMany($purchase_order_items);

            });

        }


        if($po->status->name === "Complete") event(new PurchaseOrderWasCompletedEvent($po));

        return $po;

    }


    public function complete(){
        if($this->status->name !== "Complete")
        {
            $this->status_id =  Status::where("name","Complete")->first()->id;
            $this->date_completed = date("Y-m-d");

            event(new PurchaseOrderWasCompletedEvent($this));

            $this->update();
        }

        return $this;
    }

}
