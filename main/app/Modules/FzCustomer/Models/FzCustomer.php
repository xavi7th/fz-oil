<?php

namespace App\Modules\FzCustomer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\FzCustomer\Database\Factories\FzCustomerFactory;

/**
 * App\Modules\FzCustomer\Models\FzCustomer
 *
 * @method static \App\Modules\FzCustomer\Database\Factories\FzCustomerFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|FzCustomer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FzCustomer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FzCustomer query()
 * @mixin \Eloquent
 */
class FzCustomer extends Model
{
  use HasFactory;

  protected $fillable = [];

  const DASHBOARD_ROUTE_PREFIX = 'customers';
  const ROUTE_NAME_PREFIX = 'customer.';



  protected static function newFactory()
  {
    return FzCustomerFactory::new();
  }
}
