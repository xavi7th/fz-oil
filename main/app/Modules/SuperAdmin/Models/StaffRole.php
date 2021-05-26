<?php

namespace App\Modules\SuperAdmin\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\SuperAdmin\Database\Factories\StaffRoleFactory;

class StaffRole extends Model
{
  use HasFactory;

  protected $fillable = ['role_name', 'role_slug'];

  protected static function newFactory()
  {
    return StaffRoleFactory::new();
  }

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
}
