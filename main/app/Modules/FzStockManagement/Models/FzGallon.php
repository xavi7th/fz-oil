<?php

namespace App\Modules\FzStockManagement\Models;


use App\Modules\FzStockManagement\Models\FzStock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\FzStockManagement\Database\Factories\FzGallonFactory;

/**
 * App\Modules\FzStockManagement\Models\FzGallon
 *
 * @method static \App\Modules\FzStockManagement\Database\Factories\FzGallonFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|FzGallon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FzGallon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FzGallon query()
 * @mixin \Eloquent
 */
class FzGallon extends FzStock
{
    use HasFactory;

    protected $fillable = [];

  const DASHBOARD_ROUTE_PREFIX = 'product-types';
  const ROUTE_NAME_PREFIX = 'producttype.';



    protected static function newFactory()
    {
        return FzGallonFactory::new();
    }
}
