<?php

namespace Modules\BakeryManager\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Rawmaterial
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int|null $materialtype_id
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Materialtype|null $materialtype
 * @property Collection|Rawmaterialbatch[] $rawmaterialbatches
 *
 * @package App\Models
 */
class Rawmaterial extends Model
{
    protected $table = 'rawmaterials';

    protected $casts = [
        'materialtype_id' => 'int',
        'status' => 'bool',
        'quantity' => 'float',
        'cost_price' => 'float'
    ];

    protected $fillable = [
        'name',
        'description',
        'materialtype_id',
        'quantity',
        'cost_price',
        'status'
    ];


    public static $fields = [
        'name',
        'description',
        'materialtype_id',
    ];

    public function materialtype()
    {
        return $this->belongsTo(Materialtype::class);
    }

    public function rawmaterialbatches()
    {
        return $this->hasMany(Rawmaterialbatch::class);
    }


    public function updateAvailableQuantity()
    {
        $this->quantity = $this->rawmaterialbatches()->where("quantity",">",0)->sum("quantity");
        $this->update();
    }


    public function scopefilterMaterial($query,$string)
    {

        $string =  explode(' ', $string);

        return $query ->where(function($q) use ($string){
            $q->where('status',1);
            $q->where(function($sub) use (&$string){
                foreach ($string as $char) {
                    $sub->where('name', 'LIKE', "%{$char}%");
                }
            });
        });

    }


    public function scopeavailable($query)
    {
        return $query->where("quantity",">",0);
    }


}
