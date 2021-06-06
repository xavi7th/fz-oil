<?php

namespace App\Modules\FzStockManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\FzStockManagement\Database\Factories\FzPriceBatchFactory;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Modules\FzStockManagement\Models\FzPriceBatch
 *
 * @property int $id
 * @property int $fz_product_type_id
 * @property string $cost_price
 * @property string $selling_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \App\Modules\FzStockManagement\Database\Factories\FzPriceBatchFactory factory(...$parameters)
 * @method static Builder|FzPriceBatch gallon()
 * @method static Builder|FzPriceBatch newModelQuery()
 * @method static Builder|FzPriceBatch newQuery()
 * @method static Builder|FzPriceBatch oil()
 * @method static Builder|FzPriceBatch query()
 * @method static Builder|FzPriceBatch whereCostPrice($value)
 * @method static Builder|FzPriceBatch whereCreatedAt($value)
 * @method static Builder|FzPriceBatch whereDeletedAt($value)
 * @method static Builder|FzPriceBatch whereFzProductTypeId($value)
 * @method static Builder|FzPriceBatch whereId($value)
 * @method static Builder|FzPriceBatch whereSellingPrice($value)
 * @method static Builder|FzPriceBatch whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
