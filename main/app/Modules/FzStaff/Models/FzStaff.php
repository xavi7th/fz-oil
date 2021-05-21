<?php

namespace App\Modules\FzStaff\Models;

use App\Modules\SuperAdmin\Traits\IsAStaff;
use App\User;

/**
 * App\Modules\FzStaff\Models\FzStaff
 *
 * @property-read mixed $full_name
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-write mixed $password
 * @method static \Illuminate\Database\Eloquent\Builder|FzStaff active()
 * @method static \Illuminate\Database\Eloquent\Builder|FzStaff newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FzStaff newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FzStaff query()
 * @mixin \Eloquent
 */
class FzStaff extends User
{
  use IsAStaff;

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
}
