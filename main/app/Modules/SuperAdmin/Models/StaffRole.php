<?php

namespace App\Modules\SuperAdmin\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\SuperAdmin\Database\Factories\StaffRoleFactory;

/**
 * App\Modules\SuperAdmin\Models\StaffRole
 *
 * @property int $id
 * @property string $role_name
 * @property string $role_slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StaffRole whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaffRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaffRole whereRoleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaffRole whereRoleSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaffRole whereUpdatedAt($value)
 * @method static \App\Modules\SuperAdmin\Database\Factories\StaffRoleFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|StaffRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StaffRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StaffRole query()
 * @mixin \Eloquent
 */
class StaffRole extends Model
{
  use HasFactory;

  protected $fillable = ['role_name', 'role_slug'];

  static function salesRepId(): int
  {
    return self::where('role_slug', 'sales-rep')->first()->id;
  }

  static function superAdminId(): int
  {
    return self::where('role_slug', 'super-admin')->first()->id;
  }

  static function supervisorId(): int
  {
    return self::where('role_slug', 'supervisor')->first()->id;
  }

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($staff_role) {
      $staff_role->role_slug = Str::slug($staff_role->role_name);
    });
  }

  protected static function newFactory()
  {
    return StaffRoleFactory::new();
  }
}
