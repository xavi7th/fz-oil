<?php

namespace App\Modules\FzStockManagement\Models;

use App\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\PurchaseOrder\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\FzStockManagement\Database\Factories\FzStockFactory;

/**
 * App\Modules\FzStockManagement\Models\FzStock
 *
 * @method static \Illuminate\Database\Eloquent\Builder|FzStock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FzStock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FzStock query()
 * @mixin \Eloquent
 */
class FzStock extends BaseModel
{
  use HasFactory;

  protected $table = "fz_stock";
  protected $fillable = ['fz_product_type_id', 'fz_price_batch_id', 'stock_quantity',];

  const DASHBOARD_ROUTE_PREFIX = 'fz-stock-management';
  const ROUTE_NAME_PREFIX = 'fzstockmanagement.';

  public function product_type()
  {
    return $this->belongsTo(FzProductType::class, 'fz_product_type_id');
  }

  public function price_batch()
  {
    return $this->belongsTo(FzPriceBatch::class, 'fz_price_batch_id');
  }

  public function purchase_orders()
  {
    return $this->hasMany(PurchaseOrder::class, 'fz_product_type_id', 'fz_product_type_id')->where('fz_price_batch_id', $this->fz_price_batch_id);
  }

  static function getStock(int $fz_product_type_id, int $fz_price_batch_id = null): ?self
  {
    return self::where(function (Builder $query) use ($fz_product_type_id, $fz_price_batch_id) {
      $query->where('fz_product_type_id', $fz_product_type_id);
      if ($fz_price_batch_id) {
        $query->where('fz_price_batch_id', $fz_price_batch_id);
      }
    })->first();
  }

  public function incrementStock(int $quantity): int
  {
    return $this->increment('stock_quantity', $quantity);
  }

  public function decrementStock(int $quantity): int
  {
    return $this->decrement('stock_quantity', $quantity);
  }

  public function getCostPriceAttribute()
  {
    return $this->price_batch->cost_price;
  }

  public function getSellingPriceAttribute()
  {
    return $this->price_batch->selling_price;
  }

  public function getSwapValueAttribute()
  {
    return $this->product_type->swap_value;
  }

  public function scopeGallon(Builder $query)
  {
    $query->where('fz_product_type_id', FzProductType::gallonId());
  }

  public function scopeOil(Builder $query)
  {
    $query->where('fz_product_type_id', FzProductType::oilId());
  }


  protected static function newFactory()
  {
    return FzStockFactory::new();
  }
}
