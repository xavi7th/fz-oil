<?php

namespace App\Modules\FzStockManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\FzStockManagement\Database\Factories\FzProductTypeFactory;

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

    protected $fillable = [];

  const DASHBOARD_ROUTE_PREFIX = 'product-types';
  const ROUTE_NAME_PREFIX = 'producttype.';



    protected static function newFactory()
    {
        return FzProductTypeFactory::new();
    }
}
