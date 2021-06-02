<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use App\Modules\SalesRep\Models\SalesRep;
use App\Modules\SuperAdmin\Models\SuperAdmin;
use App\Modules\Supervisor\Models\Supervisor;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-write mixed $password
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|User active()
 */
class User extends Authenticatable
{
  use Notifiable, SoftDeletes;

  const TABLE_NAME = 'fz_staff';

  protected $fillable = ['email', 'user_name', 'password', 'full_name', 'phone', 'gender', 'address', 'id_url', 'staff_role_id', 'verified_at'];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'verified_at' => 'datetime',
    'last_login_at' => 'datetime',
    'is_active' =>'bool'
  ];

  public function setPasswordAttribute($value)
  {
    $this->attributes['password'] = bcrypt($value);
  }

  public function isSuperAdmin(): bool
  {
    return $this instanceof SuperAdmin;
  }

  public function isSalesRep(): bool
  {
    return $this instanceof SalesRep;
  }

  public function isFzAdmin(): bool
  {
    return false;
    return $this instanceof SalesRep;
  }

  public function is_verified():bool
  {
    return $this->isSuperAdmin() || $this->verified_at !== null;
  }

  public function get_navigation_routes(): array
  {
    return get_related_routes(strtolower($this->getType()), ['GET'], $isHeirarchical = true);
  }

  public function getDashboardRoute(): string
  {
    return strtolower($this->getType()) . '.dashboard';
  }

  public function getType(): string
  {
    return class_basename(get_class($this));
  }

  public function getUserType(): array
  {
    if ($this->isSalesRep()) {
      return ['isSalesRep' => true, 'user_type' => strtolower($this->getType())];
    } elseif ($this->isFzAdmin()) {
      return ['isFzAdmin' => true,'user_type' => strtolower($this->getType())];
    } elseif ($this->isSuperAdmin()) {
      return ['isSuperAdmin' => true, 'user_type' => strtolower($this->getType())];
    }
  }

  static function findUserByEmail(string $email): self
  {
    return SalesRep::findByEmail($email) ?? Supervisor::findByEmail($email);
  }

  public function toFlare(): array
  {
    // Only `id` will be sent to Flare.
    return [
      'id' => $this->id
    ];
  }

  /**
   * Route notifications for the Slack channel.
   *
   * @param  \Illuminate\Notifications\Notification  $notification
   * @return string
   */
  public function routeNotificationForSlack($notification)
  {
    return config('logging.channels.slack.ecommerce_webhook');
  }
}
