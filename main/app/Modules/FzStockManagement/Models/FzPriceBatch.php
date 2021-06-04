<?php

namespace App\Modules\FzStockManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\FzStockManagement\Database\Factories\FzPriceBatchFactory;
use Illuminate\Database\Eloquent\Builder;

class FzPriceBatch extends Model
{
  use HasFactory;

  protected $fillable = ['fz_product_type_id', 'cost_price', 'selling_price',];

  const DASHBOARD_ROUTE_PREFIX = 'price-batches';
  const ROUTE_NAME_PREFIX = 'pricebatches.';

  public function scopeOil(Builder $query)
  {
    $query->where('fz_product_type_id', FzProductType::oilId());
  }

  public function scopeGallon(Builder $query)
  {
    $query->where('fz_product_type_id', FzProductType::gallonId());
  }

  protected static function newFactory()
  {
    return FzPriceBatchFactory::new();
  }
}
