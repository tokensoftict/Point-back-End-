<?php

namespace Modules\StockModule\Entities;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Entities\User;
use Modules\Settings\Entities\Brand;
use Modules\Settings\Entities\Category;
use Modules\Settings\Entities\Classification;
use Modules\Settings\Entities\Manufacturer;
use Modules\Settings\Entities\Productgroup;
use Modules\Settings\Entities\Supplier;
use Modules\Settings\Transformers\BrandResource;
use Modules\Settings\Transformers\CategoryResource;
use Modules\Settings\Transformers\ClassificationResource;
use Modules\Settings\Transformers\ManufacturerResource;
use Modules\Settings\Transformers\ProductGroupResource;
use Modules\Settings\Transformers\SupplierResource;
use Modules\StockModule\Http\Requests\StockRequest;

/**
 * Class Stock
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property string|null $code
 * @property int|null $category_id
 * @property int|null $manufacturer_id
 * @property int|null $classification_id
 * @property int|null $productgroup_id
 * @property int|null $brand_id
 * @property float|null $selling_price
 * @property float|null $cost_price
 * @property string|null $barcode
 * @property string|null $location
 * @property bool $expiry
 * @property int $piece
 * @property int $box
 * @property int $cartoon
 * @property bool $sachet
 * @property bool $status
 * @property float $quantity
 * @property string|null $image
 * @property int|null $user_id
 * @property int|null $last_updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Brand|null $brand
 * @property Category|null $category
 * @property Classification|null $classification
 * @property User|null $user
 * @property User|null $last_updated
 * @property Manufacturer|null $manufacturer
 * @property Productgroup|null $productgroup
 * @property Collection|Stockbatch[] $stockbatches
 *
 * @package App\Models
 */
class Stock extends Model
{
    protected $table = 'stocks';

    protected $casts = [
        'category_id' => 'int',
        'manufacturer_id' => 'int',
        'classification_id' => 'int',
        'productgroup_id' => 'int',
        'brand_id' => 'int',
        'selling_price' => 'float',
        'cost_price' => 'float',
        'expiry' => 'bool',
        'piece' => 'int',
        'box' => 'int',
        'cartoon' => 'int',
        'sachet' => 'bool',
        'status' => 'bool',
        'quantity' => 'float',
        'user_id' => 'int',
        'last_updated_by' => 'int'
    ];

    protected $fillable = [
        'name',
        'description',
        'code',
        'category_id',
        'manufacturer_id',
        'classification_id',
        'productgroup_id',
        'brand_id',
        'selling_price',
        'cost_price',
        'barcode',
        'location',
        'expiry',
        'piece',
        'box',
        'cartoon',
        'sachet',
        'status',
        'quantity',
        'image',
        'user_id',
        'last_updated_by'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function classification()
    {
        return $this->belongsTo(Classification::class);
    }

    public function last_updated()
    {
        return $this->belongsTo(User::class,"last_updated_by");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function productgroup()
    {
        return $this->belongsTo(Productgroup::class);
    }

    public function stockbatches()
    {
        return $this->hasMany(Stockbatch::class);
    }


    public function scopeavailable($query)
    {
        return $query->enabled()->where("quantity",">",0);
    }

    public function scopeenabled($query)
    {
        return $query->where("status",1);
    }

    public function scopedisabled($query)
    {
        return $query->where("status",0);
    }

    public function scopequickWith($query)
    {
        return $query->with(['category','productgroup']);
    }

    public function scopefilterStock($query,$string){

        $string =  explode(' ', $string);

        return $query->where(function($q) use ($string){
            $q->where(function($sub) use (&$string){
                foreach ($string as $char) {
                    $sub->where('name', 'LIKE', "%{$char}%");
                }
            });
        });

    }

    public function scopefilterAvailableStock($query,$string){

        $string =  explode(' ', $string);

        return $query ->enabled()->available()->where(function($q) use ($string){
            $q->where(function($sub) use (&$string){
                foreach ($string as $char) {
                    $sub->where('name', 'LIKE', "%{$char}%");
                }
            });
        });

    }


    public static function Requesites()
    {
        return [
            'brands' => BrandResource::collection(Brand::where("status",1)->get()),
            'categories' => CategoryResource::collection(Category::where("status",1)->get()),
            'classification' => ClassificationResource::collection(Classification::where("status",1)->get()),
            'manufacturer' => ManufacturerResource::collection(Manufacturer::where("status",1)->get()),
            'productgroup' => ProductGroupResource::collection(Productgroup::where("status",1)->get()),
            'suppliers' => SupplierResource::collection(Supplier::where("status",1)->get())
        ];
    }


    public static function saveStock(StockRequest $request, Stock $stock = NULL)
    {

        if($stock !== NULL)
        {
            $data = $request->all();
            $data['last_updated_by'] = auth()->id();
            $stock->update($data);
        }
        else
        {
            $data = $request->all();
            $data['last_updated_by'] = auth()->id();
            $data['user_id'] = auth()->id();
            $stock = Stock::create($data);
        }



        return $stock;

    }

}
