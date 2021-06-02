<?php

namespace App\Modules\SalesRep\Models;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\SuperAdmin\Models\StaffRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\SalesRep\Database\Factories\SalesRepFactory;
use App\Modules\SuperAdmin\Traits\IsAStaff;

/**
 * App\Modules\SalesRep\Models\SalesRep
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-write mixed $password
 * @method static \Illuminate\Database\Eloquent\Builder|User active()
 * @method static \App\Modules\SalesRep\Database\Factories\SalesRepFactory factory(...$parameters)
 * @method static Builder|SalesRep newModelQuery()
 * @method static Builder|SalesRep newQuery()
 * @method static Builder|SalesRep query()
 * @mixin \Eloquent
 */
class SalesRep extends User
{
  use HasFactory, IsAStaff;

  protected $table = parent::TABLE_NAME;
  protected $fillable = ['email', 'user_name', 'password', 'full_name', 'phone', 'gender', 'address', 'id_url', 'staff_role_id',];

  const DASHBOARD_ROUTE_PREFIX = 'sales-rep';
  const ROUTE_NAME_PREFIX = 'salesrep.';

  protected static function newFactory()
  {
    return SalesRepFactory::new();
  }

  protected static function booted()
  {
    static::addGlobalScope('salesrep', function (Builder $builder) {
      $builder->where('staff_role_id', StaffRole::salesRepId());
    });
  }
}
