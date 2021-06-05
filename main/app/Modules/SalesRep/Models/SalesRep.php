<?php

namespace App\Modules\SalesRep\Models;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\SuperAdmin\Traits\IsAStaff;
use App\Modules\SuperAdmin\Models\StaffRole;
use App\Modules\OfficeExpense\Models\OfficeExpense;
use App\Modules\PurchaseOrder\Models\PurchaseOrder;
use App\Modules\FzCustomer\Models\CreditTransaction;
use App\Modules\PurchaseOrder\Models\CashLodgement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\PurchaseOrder\Models\DirectSwapTransaction;
use App\Modules\SalesRep\Database\Factories\SalesRepFactory;

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

  const DASHBOARD_ROUTE_PREFIX = 'sales-rep';
  const ROUTE_NAME_PREFIX = 'salesrep.';

  public function expenses()
  {
    return $this->hasMany(OfficeExpense::class);
  }

  public function recorded_credit_transactions()
  {
    return $this->hasMany(CreditTransaction::class, 'recorded_by');
  }

  public function direct_swap_transactions()
  {
    return $this->hasMany(DirectSwapTransaction::class);
  }

  public function purchase_orders()
  {
    return $this->hasMany(PurchaseOrder::class);
  }

  public function cash_lodgements()
  {
    return $this->hasMany(CashLodgement::class);
  }

  public function cash_in_office(): float
  {
    return $this->recorded_credit_transactions()->cashInOffice() + $this->purchase_orders()->cashInOffice() - $this->direct_swap_transactions()->sum('amount') - $this->cash_lodgements()->sum('amount');
  }

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
