<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Bakeryproductionitem
 * 
 * @property int $id
 * @property int $rawmaterial_id
 * @property int $bakeryproduction_id
 * @property int $user_id
 * @property Carbon $production_date
 * @property Carbon|null $production_time
 * @property float $quantity
 * @property float $recycle
 * @property float $roughs
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Rawmaterial $rawmaterial
 * @property User $user
 *
 * @package App\Models
 */
class Bakeryproductionitem extends Model
{
	protected $table = 'bakeryproductionitems';

	protected $casts = [
		'rawmaterial_id' => 'int',
		'bakeryproduction_id' => 'int',
		'user_id' => 'int',
		'quantity' => 'float',
		'recycle' => 'float',
		'roughs' => 'float'
	];

	protected $dates = [
		'production_date',
		'production_time'
	];

	protected $fillable = [
		'rawmaterial_id',
		'bakeryproduction_id',
		'user_id',
		'production_date',
		'production_time',
		'quantity',
		'recycle',
		'roughs'
	];

	public function rawmaterial()
	{
		return $this->belongsTo(Rawmaterial::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
