<?php

namespace App\Modules\AppUser\Models;

use App\Modules\SuperAdmin\Traits\IsAStaff;
use App\User;

/**
 * App\Modules\AppUser\Models\AppUser
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
 * @method static \Illuminate\Database\Eloquent\Builder|AppUser active()
 * @method static \Illuminate\Database\Eloquent\Builder|AppUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|AppUser whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppUser whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppUser whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppUser whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppUser whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppUser whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppUser whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppUser whereIgHandle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppUser whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppUser whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppUser whereOtpVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppUser wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppUser wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppUser whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppUser whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AppUser extends User
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
