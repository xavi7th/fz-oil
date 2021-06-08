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
 * @property int $id
 * @property string $email
 * @property string $user_name
 * @property string $full_name
 * @property string $gender
 * @property string $phone
 * @property string $address
 * @property string|null $id_url
 * @property bool $is_active
 * @property int $staff_role_id
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $last_login_at
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-write mixed $password
 * @method static Builder|SuperAdmin whereAddress($value)
 * @method static Builder|SuperAdmin whereCreatedAt($value)
 * @method static Builder|SuperAdmin whereDeletedAt($value)
 * @method static Builder|SuperAdmin whereEmail($value)
 * @method static Builder|SuperAdmin whereFullName($value)
 * @method static Builder|SuperAdmin whereGender($value)
 * @method static Builder|SuperAdmin whereId($value)
 * @method static Builder|SuperAdmin whereIdUrl($value)
 * @method static Builder|SuperAdmin whereIsActive($value)
 * @method static Builder|SuperAdmin whereLastLoginAt($value)
 * @method static Builder|SuperAdmin wherePassword($value)
 * @method static Builder|SuperAdmin wherePhone($value)
 * @method static Builder|SuperAdmin whereRememberToken($value)
 * @method static Builder|SuperAdmin whereStaffRoleId($value)
 * @method static Builder|SuperAdmin whereUpdatedAt($value)
 * @method static Builder|SuperAdmin whereUserName($value)
 * @method static Builder|SuperAdmin whereVerifiedAt($value)
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

  const DASHBOARD_ROUTE_PREFIX = 'super-admins';
  const ROUTE_NAME_PREFIX = 'superadmin.';

  static function cashInOffice(): float
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
