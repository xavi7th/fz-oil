<?php

namespace App\Modules\FzStockManagement\Models;

use App\Modules\FzStockManagement\Models\FzStock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\FzStockManagement\Database\Factories\FzOilFactory;

/**
 * App\Modules\FzStockManagement\Models\FzOil
 *
 * @method static \App\Modules\FzStockManagement\Database\Factories\FzOilFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|FzOil newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FzOil newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FzOil query()
 * @mixin \Eloquent
 */
class FzOil extends FzStock
{
    use HasFactory;

    protected $fillable = [];

  const DASHBOARD_ROUTE_PREFIX = 'product-types';
  const ROUTE_NAME_PREFIX = 'producttype.';



    protected static function newFactory()
    {
        return FzOilFactory::new();
    }
}
