<?php

namespace App\Modules\FzStockManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\FzStockManagement\Database\Factories\FzProductBatchFactory;

/**
 * App\Modules\FzStockManagement\Models\FzProductBatch
 *
 * @method static \App\Modules\FzStockManagement\Database\Factories\FzProductBatchFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|FzProductBatch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FzProductBatch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FzProductBatch query()
 * @mixin \Eloquent
 */
class FzProductBatch extends Model
{
    use HasFactory;

    protected $fillable = [];

  const DASHBOARD_ROUTE_PREFIX = 'product-batches';
  const ROUTE_NAME_PREFIX = 'productbatch.';



    protected static function newFactory()
    {
        return FzProductBatchFactory::new();
    }
}
