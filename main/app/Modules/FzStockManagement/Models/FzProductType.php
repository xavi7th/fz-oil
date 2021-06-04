<?php

namespace App\Modules\FzStockManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\FzStockManagement\Database\Factories\FzProductTypeFactory;
use App\Modules\PurchaseOrder\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Modules\FzStockManagement\Models\FzProductType
 *
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
