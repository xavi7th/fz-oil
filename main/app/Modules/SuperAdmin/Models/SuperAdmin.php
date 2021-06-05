<?php

namespace App\Modules\SuperAdmin\Models;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\SuperAdmin\Models\StaffRole;
use App\Modules\PurchaseOrder\Models\CashLodgement;
use App\Modules\PurchaseOrder\Models\PurchaseOrder;
use App\Modules\FzCustomer\Models\CreditTransaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\PurchaseOrder\Models\DirectSwapTransaction;
use App\Modules\SuperAdmin\Database\Factories\SuperAdminFactory;

/**
 * App\Modules\SuperAdmin\Models\SuperAdmin
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-write mixed $password
 * @method static \Illuminate\Database\Eloquent\Builder|User active()
 * @method static \App\Modules\SuperAdmin\Database\Factories\SuperAdminFactory factory(...$parameters)
 * @method static Builder|SuperAdmin newModelQuery()
 * @method static Builder|SuperAdmin newQuery()
 * @method static Builder|SuperAdmin query()
 * @mixin \Eloquent
 */
class SuperAdmin extends User
{
  use HasFactory;

  protected $table = parent::TABLE_NAME;

  const DASHBOARD_ROUTE_PREFIX = 'super-admin';
  const ROUTE_NAME_PREFIX = 'superadmin.';

  static function cash_in_office(): float
  {
    return CreditTransaction::cashInOffice() + PurchaseOrder::cashInOffice() - DirectSwapTransaction::cash()->sum('amount') - CashLodgement::sum('amount');
  }

  protected static function newFactory()
  {
      return SuperAdminFactory::new();
  }

  protected static function booted()
  {
    static::addGlobalScope('superadmin', function (Builder $builder) {
      $builder->where('staff_role_id', StaffRole::superAdminId());
    });
  }
}
