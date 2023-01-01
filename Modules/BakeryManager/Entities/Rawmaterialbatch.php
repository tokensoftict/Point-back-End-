<?php

namespace Modules\BakeryManager\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Entities\Supplier;

/**
 * Class Rawmaterialbatch
 *
 * @property int $id
 * @property Carbon|null $received_date
 * @property Carbon|null $expiry_date
 * @property float $quantity
 * @property int|null $supplier_id
 * @property int|null $rawmaterial_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Rawmaterial|null $rawmaterial
 * @property Supplier|null $supplier
 *
 * @package App\Models
 */
class Rawmaterialbatch extends Model
{
    protected $table = 'rawmaterialbatch';

    protected $casts = [
        'quantity' => 'float',
        'supplier_id' => 'int',
        'rawmaterial_id' => 'int'
    ];

    protected $dates = [
        'received_date',
        'expiry_date'
    ];

    protected $fillable = [
        'received_date',
        'expiry_date',
        'quantity',
        'supplier_id',
        'rawmaterial_id'
    ];

    public function rawmaterial()
    {
        return $this->belongsTo(Rawmaterial::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

}
