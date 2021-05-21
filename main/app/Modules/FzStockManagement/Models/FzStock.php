<?php

namespace App\Modules\FzStockManagement\Models;

use App\BaseModel;

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

  protected $fillable = [];

  const DASHBOARD_ROUTE_PREFIX = 'fz-stock-management';
  const ROUTE_NAME_PREFIX = 'fzstockmanagement.';
}
