<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\BakeryManager\Entities;


use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Entities\User;
use Modules\BakeryManager\Http\Requests\ProductionRequest;
use Modules\Settings\Entities\Branch;

/**
 * Class Bakeryproduction
 *
 * @property int $id
 * @property string $name
 * @property Carbon $production_date
 * @property Carbon|null $production_time
 * @property int $status_id
 * @property string|null $remark
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Status $status
 * @property User $user
 * @property User $completed
 * @package App\Models
 */
class Bakeryproduction extends Model
{
	protected $table = 'bakeryproductions';

	protected $casts = [
		'status_id' => 'int',
		'user_id' => 'int',
        'completed_id' => 'int'
	];


	protected $fillable = [
		'name',
		'production_date',
		'production_time',
		'status_id',
		'remark',
		'user_id',
        'completed_id',
        'branch_id'
	];

    public static $fields = [
        'name',
        'production_date',
        'production_time',
        'status_id',
        'remark',
        'user_id',
    ];

    public static $updateFields = [
        'name',
        'production_date',
        'production_time',
        'remark',
        'status_id'
    ];

    protected $with = ['status'];

    protected $appends = ["total_material","total_product","total_estimate_total","profit"];


    public function getTotalMaterialAttribute()
    {
        return $this->bakery_production_material_items()->sum("total");
    }

    public function getTotalProductAttribute()
    {
        return $this->bakery_production_products_items()->sum("total");
    }

    public function getTotalEstimateTotalAttribute()
    {
        return $this->bakery_production_products_items()->sum("estimate_total");
    }

    public function getProfitAttribute()
    {
        return ($this->status_id == 4 ? $this->total_estimate_total : $this->total_product) -  $this->total_material;
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function completed()
    {
        return $this->belongsTo(User::class,"completed_id");
    }

    public function bakery_production_material_items()
    {
        return $this->hasMany(BakeryProductionMaterialItem::class);
    }


    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function bakery_production_products_items()
    {
        return $this->hasMany(BakeryProductionProductsItem::class);
    }

    public function scopefilterdata($query)
    {
        if(request()->get("filter"))
        {
            foreach (json_decode(request()->get("filter"),true) as $key => $value) {
                if($key === "between")
                {
                    $query->whereBetween("production_date",$value );
                }
                else {
                    $query->where($key, $value);
                }
            }
        }

        return $query;
    }



    public function approve()
    {
        foreach ($this->bakery_production_material_items()->get() as $item)
        {
            $item->rawmaterial->{$this->branch->quantity_column} =   $item->rawmaterial->{$this->branch->quantity_column} - $item->quantity;
            $item->rawmaterial->update();
        }

        $this->status_id = Status::where("name","Approved")->first()->id;
        $this->save();
    }

    public function decline()
    {
        $this->status_id = Status::where("name","Declined")->first()->id;
        $this->save();
    }

    public function complete()
    {
        $status = Status::where("name","Complete")->first()->id;

        if($this->status_id === $status)
        {
            return $this;
        }

        $products = json_decode(request()->get("products"),true);

        if(!$products) return $this;

        \DB::transaction(function () use(&$products,&$status){

            foreach ($products as $product)
            {
                $p = $this->bakery_production_products_items()->with(['stock'])->where("id",$product['product_item_id'])->first();

                $p->quantity = $product['quantity'];
                $p->total = $product['quantity'] * $p->selling_price;

                $p->update();

                $p->stock->{$this->branch->quantity_column} +=  $p->quantity;

                $p->stock->save();
            }

            $this->status_id = $status;

            $this->completed_id = auth()->id();

            $this->save();

        });

    }


    public static function saveProduction(ProductionRequest $request, Bakeryproduction $production = null)
    {

        $production_time = Carbon::parse($request->get('production_time'))->toTimeString();

        $request->request->add(['status_id'=>Status::where("name",'draft')->first()->id]);

        $request->request->add(["user_id"=>auth()->id()]);

        $request->request->remove("production_time");

        $materials = json_decode($request->get("items"),true);

        $products = json_decode($request->get("products"),true);

        $request->request->remove("products");

        $request->request->remove("items");

        $data = $request->only(self::$fields);
        $data['branch_id'] = getBranch()->id;
        $data['production_time'] =  $production_time;

        $production = self::create($data);

        \DB::transaction(function() use(&$production, &$request, &$materials, &$products, &$production_time){

            $_materials = [];

            $_products = [];

            if($production)
            {
                $data = $request->only(self::$updateFields);
                $data['branch_id'] = getBranch()->id;

                $production->update($request->only($data));

                $production->bakery_production_material_items()->delete();
                $production->bakery_production_products_items()->delete();
            }
            else
            {
                $data = $request->only(self::$fields);
                $data['branch_id'] = getBranch()->id;
                $data['production_time'] =  $production_time;
                $production = self::create($data);
            }

            foreach ($materials as $material)
            {
                $_materials[] = new BakeryProductionMaterialItem(
                    [
                        "rawmaterial_id" => $material['id'],
                        "status_id" => Status::where("name","Pending")->first()->id,
                        "quantity" => $material['quantity'],
                        "cost_price" => $material['cost_price'],
                        "total" => ($material["quantity"] * $material['cost_price']),
                        "production_date" => $request->get("production_date"),
                        'branch_id' => getBranch()->id
                    ]
                );
            }

            foreach ($products as $product)
            {
                $_products[] = new BakeryProductionProductsItem(
                    [
                        "stock_id" => $product['id'],
                        "status_id" =>  Status::where("name","Pending")->first()->id,
                        "estimate_quantity" => $product['estimate_quantity'],
                        "selling_price" =>  $product['selling_price'],
                        "estimate_total" => $product['estimate_quantity'] * $product['selling_price'],
                        "production_date" => $request->get("production_date"),
                        'branch_id' => getBranch()->id
                    ]
                );
            }

            $production->bakery_production_material_items()->saveMany($_materials);

            $production->bakery_production_products_items()->saveMany($_products);

        });


        return $production;

    }


}
