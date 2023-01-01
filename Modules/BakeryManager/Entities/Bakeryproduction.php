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
 *
 * @package App\Models
 */
class Bakeryproduction extends Model
{
	protected $table = 'bakeryproductions';

	protected $casts = [
		'status_id' => 'int',
		'user_id' => 'int'
	];

	protected $dates = [
		'production_date',
		'production_time'
	];

	protected $fillable = [
		'name',
		'production_date',
		'production_time',
		'status_id',
		'remark',
		'user_id'
	];

    public static $fields = [
        'name',
        'production_date',
        'production_time',
        'status_id',
        'remark',
        'user_id'
    ];

    public static $updateFields = [
        'name',
        'production_date',
        'production_time',
        'remark',
        'status_id'
    ];


    protected $appends = ["total_material","total_product","profit"];


    public function getTotalMaterialAttribute()
    {
        return $this->bakery_production_material_items()->sum("total");
    }

    public function getTotalProductAttribute()
    {
        return $this->bakery_production_products_items()->sum("total");
    }

    public function getProfitAttribute()
    {
        return $this->total_product -  $this->total_material;
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bakery_production_material_items()
    {
        return $this->hasMany(BakeryProductionMaterialItem::class);
    }

    public function bakery_production_products_items()
    {
        return $this->hasMany(BakeryProductionProductsItem::class);
    }


    public static function saveProduction(ProductionRequest $request, Bakeryproduction $production = null)
    {

        $request->request->add(['status_id'=>Status::where("name",'draft')->first()->id]);

        $request->request->add(["user_id"=>auth()->id()]);

        $materials = json_decode($request->get("items"),true);

        $products = json_decode($request->get("products"),true);


        \DB::transaction(function() use(&$production, &$request, &$materials, &$products){

            $_materials = [];

            $_products = [];

            if($production)
            {
                $production->update($request->only(self::$updateFields));

                $production->bakery_production_material_items()->delete();
                $production->bakery_production_products_items()->delete();
            }
            else
            {
                $production = self::create($request->only(self::$fields));
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
                        "production_date" => $request->get("production_date")
                    ]
                );
            }

            foreach ($products as $product)
            {
                $_products[] = new BakeryProductionProductsItem(
                    [
                        "stock_id" => $product['id'],
                        "status_id" =>  Status::where("name","Pending")->first()->id,
                        "quantity" => $product['quantity'],
                        "selling_price" =>  $product['selling_price'],
                        "total" => $product['quantity'] * $product['selling_price'],
                        "production_date" => $request->get("production_date"),
                        "recycle" => 0,
                        "roughs" => 0
                    ]
                );
            }

            $production->bakery_production_material_items()->saveMany($_materials);

            $production->bakery_production_products_items()->saveMany($_products);

        });


        return $production;

    }


}
