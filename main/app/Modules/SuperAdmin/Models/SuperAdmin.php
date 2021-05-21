<?php

namespace App\Modules\SuperAdmin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\SuperAdmin\Database\Factories\SuperAdminFactory;

/**
 * App\Modules\SuperAdmin\Models\SuperAdmin
 *
 * @method static \App\Modules\SuperAdmin\Database\Factories\SuperAdminFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|SuperAdmin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SuperAdmin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SuperAdmin query()
 * @mixin \Eloquent
 */
class SuperAdmin extends Model
{
  use HasFactory;

  protected $fillable = [];

  const DASHBOARD_ROUTE_PREFIX = 'superadmin';
  const ROUTE_NAME_PREFIX = 'superadmin.';


  protected static function newFactory()
  {
      return SuperAdminFactory::new();
  }
}
