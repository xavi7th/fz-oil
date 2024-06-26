<?php

namespace App\Modules\FzStockManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\PurchaseOrder\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\PurchaseOrder\Models\DirectSwapTransaction;
use App\Modules\FzStockManagement\Database\Factories\FzProductTypeFactory;

/**
 * App\Modules\FzStockManagement\Models\FzProductType
 *
 * @property int $id
 * @property string $product_type
 * @property string|null $swap_value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|DirectSwapTransaction[] $direct_swap_transactions
 * @property-read int|null $direct_swap_transactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|PurchaseOrder[] $purchase_orders
 * @property-read int|null $purchase_orders_count
 * @method static Builder|FzProductType gallon()
 * @method static Builder|FzProductType oil()
 * @method static Builder|FzProductType whereCreatedAt($value)
 * @method static Builder|FzProductType whereDeletedAt($value)
 * @method static Builder|FzProductType whereId($value)
 * @method static Builder|FzProductType whereProductType($value)
 * @method static Builder|FzProductType whereSwapValue($value)
 * @method static Builder|FzProductType whereUpdatedAt($value)
 * @method static \App\Modules\FzStockManagement\Database\Factories\FzProductTypeFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|FzProductType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FzProductType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FzProductType query()
 * @mixin \Eloquent
 */
class FzProductType extends Model
{
  use HasFactory;

  protected $fillable = ['product_type', 'swap_value',];

  const DASHBOARD_ROUTE_PREFIX = 'product-types';
  const ROUTE_NAME_PREFIX = 'producttype.';

  public function purchase_orders()
  {
    return $this->hasMany(PurchaseOrder::class);
  }

  public function direct_swap_transactions()
  {
    return $this->hasMany(DirectSwapTransaction::class);
  }

  public function isGallon(): bool
  {
    return $this->product_type == 'gallon';
  }

  public function isOil(): bool
  {
    return $this->product_type == 'oil';
  }

  static function gallonId(): int
  {
    return self::gallon()->first()->id;
  }

  static function oilId(): int
  {
    return self::oil()->first()->id;
  }

  public function scopeOil(Builder $query)
  {
    return $query->where('product_type', 'LIKE', '%oil%');
  }

  public function scopeGallon(Builder $query)
  {
    return $query->where('product_type', 'LIKE', '%gallon%');
  }

  protected static function newFactory()
  {
    return FzProductTypeFactory::new();
  }
}
