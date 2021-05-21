<?php

namespace App\Modules\FzStaff\Models;

use App\User;
use App\Modules\SuperAdmin\Traits\IsAStaff;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\FzStaff\Database\Factories\FzStaffFactory;

/**
 * App\Modules\FzStaff\Models\FzStaff
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string|null $email_verified_at
 * @property string $first_name
 * @property string|null $last_name
 * @property string $phone
 * @property string|null $otp_verified_at
 * @property string $address
 * @property string $city
 * @property string|null $ig_handle
 * @property string|null $avatar
 * @property int $is_active
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $full_name
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|FzStaff active()
 * @method static \App\Modules\FzStaff\Database\Factories\FzStaffFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|FzStaff newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FzStaff newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FzStaff query()
 * @method static \Illuminate\Database\Eloquent\Builder|FzStaff whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FzStaff whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FzStaff whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FzStaff whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FzStaff whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FzStaff whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FzStaff whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FzStaff whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FzStaff whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FzStaff whereIgHandle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FzStaff whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FzStaff whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FzStaff whereOtpVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FzStaff wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FzStaff wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FzStaff whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FzStaff whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FzStaff extends User
{
  use IsAStaff, HasFactory;

  protected $fillable = [

  ];
  protected $hidden = [
  ];

  protected $casts = [
  ];

  const DASHBOARD_ROUTE_PREFIX = 'user';
  const ROUTE_NAME_PREFIX = 'user.';


  public function getFullNameAttribute()
  {
    return $this->first_name . ' ' . $this->last_name;
  }

  protected static function newFactory()
  {
      return FzStaffFactory::new();
  }
}
