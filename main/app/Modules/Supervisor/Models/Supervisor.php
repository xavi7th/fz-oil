<?php

namespace App\Modules\Supervisor\Models;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\SuperAdmin\Models\StaffRole;
use App\Modules\SuperAdmin\Models\SuperAdmin;
use App\Modules\SuperAdmin\Traits\IsAStaff;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Supervisor\Database\Factories\SupervisorFactory;

/**
 * App\Modules\Supervisor\Models\Supervisor
 *
 * @property int $id
 * @property string $email
 * @property string $user_name
 * @property string $password
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
 * @method static Builder|Supervisor active()
 * @method static \App\Modules\Supervisor\Database\Factories\SupervisorFactory factory(...$parameters)
 * @method static Builder|Supervisor newModelQuery()
 * @method static Builder|Supervisor newQuery()
 * @method static Builder|Supervisor query()
 * @method static Builder|Supervisor whereAddress($value)
 * @method static Builder|Supervisor whereCreatedAt($value)
 * @method static Builder|Supervisor whereDeletedAt($value)
 * @method static Builder|Supervisor whereEmail($value)
 * @method static Builder|Supervisor whereFullName($value)
 * @method static Builder|Supervisor whereGender($value)
 * @method static Builder|Supervisor whereId($value)
 * @method static Builder|Supervisor whereIdUrl($value)
 * @method static Builder|Supervisor whereIsActive($value)
 * @method static Builder|Supervisor whereLastLoginAt($value)
 * @method static Builder|Supervisor wherePassword($value)
 * @method static Builder|Supervisor wherePhone($value)
 * @method static Builder|Supervisor whereRememberToken($value)
 * @method static Builder|Supervisor whereStaffRoleId($value)
 * @method static Builder|Supervisor whereUpdatedAt($value)
 * @method static Builder|Supervisor whereUserName($value)
 * @method static Builder|Supervisor whereVerifiedAt($value)
 * @mixin \Eloquent
 */
class Supervisor extends User
{
  use HasFactory, IsAStaff;

  protected $table = parent::TABLE_NAME;

  const DASHBOARD_ROUTE_PREFIX = 'supervisors';
  const ROUTE_NAME_PREFIX = 'supervisor.';

  public function cashInOffice()
  {
    return SuperAdmin::cashInOffice();
  }

  protected static function newFactory()
  {
    return SupervisorFactory::new();
  }

  protected static function booted()
  {
    static::addGlobalScope('supervisor', function (Builder $builder) {
      $builder->where('staff_role_id', StaffRole::supervisorId());
    });
  }
}
