<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\BakeryManager\Entities;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Entities\User;
use Modules\StockModule\Entities\Stock;

/**
 * Class Bakeryproductionlog
 *
 * @property int $id
 * @property int $bakeryproduction_id
 * @property int $stock_id
 * @property float $quantity
 * @property float $rough
 * @property float $bags
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Bakeryproduction $bakeryproduction
 * @property Stock $stock
 * @property User $user
 *
 * @package App\Models
 */
class Bakeryproductionlog extends Model
{
	protected $table = 'bakeryproductionlogs';

	protected $casts = [
		'bakeryproduction_id' => 'int',
		'stock_id' => 'int',
		'quantity' => 'float',
		'rough' => 'float',
		'bags' => 'float',
		'user_id' => 'int'
	];

	protected $fillable = [
		'bakeryproduction_id',
		'stock_id',
		'quantity',
		'rough',
		'bags',
		'user_id'
	];

	public function bakeryproduction()
	{
		return $this->belongsTo(Bakeryproduction::class);
	}

	public function stock()
	{
		return $this->belongsTo(Stock::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
