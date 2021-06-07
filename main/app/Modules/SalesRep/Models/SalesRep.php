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
 * @property bool $is_active
 * @property int $staff_role_id
 * @property int $id
 * @property string $email
 * @property string $user_name
 * @property string $full_name
 * @property string $gender
 * @property string $phone
 * @property string $address
 * @property string|null $id_url
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $last_login_at
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|CashLodgement[] $cash_lodgements
 * @property-read int|null $cash_lodgements_count
 * @property-read \Illuminate\Database\Eloquent\Collection|DirectSwapTransaction[] $direct_swap_transactions
 * @property-read int|null $direct_swap_transactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|OfficeExpense[] $expenses
 * @property-read int|null $expenses_count
 * @property-read \Illuminate\Database\Eloquent\Collection|PurchaseOrder[] $purchase_orders
 * @property-read int|null $purchase_orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection|CreditTransaction[] $recorded_credit_transactions
 * @property-read int|null $recorded_credit_transactions_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-write mixed $password
 * @method static Builder|SalesRep whereAddress($value)
 * @method static Builder|SalesRep whereCreatedAt($value)
 * @method static Builder|SalesRep whereDeletedAt($value)
 * @method static Builder|SalesRep whereEmail($value)
 * @method static Builder|SalesRep whereFullName($value)
 * @method static Builder|SalesRep whereGender($value)
 * @method static Builder|SalesRep whereId($value)
 * @method static Builder|SalesRep whereIdUrl($value)
 * @method static Builder|SalesRep whereIsActive($value)
 * @method static Builder|SalesRep whereLastLoginAt($value)
 * @method static Builder|SalesRep wherePassword($value)
 * @method static Builder|SalesRep wherePhone($value)
 * @method static Builder|SalesRep whereRememberToken($value)
 * @method static Builder|SalesRep whereStaffRoleId($value)
 * @method static Builder|SalesRep whereUpdatedAt($value)
 * @method static Builder|SalesRep whereUserName($value)
 * @method static Builder|SalesRep whereVerifiedAt($value)
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

  const DASHBOARD_ROUTE_PREFIX = 'sales-reps';
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
