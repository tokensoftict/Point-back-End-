<?php

namespace Modules\BakeryManager\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Materialtype
 *
 * @property int $id
 * @property string $name
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Materialtype extends Model
{
    protected $table = 'materialtypes';

    protected $casts = [
        'status' => 'bool'
    ];

    protected $fillable = [
        'name',
        'status'
    ];

    public function rawmaterials()
    {
        return $this->hasMany(Rawmaterial::class);
    }
}

