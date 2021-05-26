<?php

namespace App\Modules\SuperAdmin\Models;

use App\Modules\FzStaff\Models\FzStaff;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\SuperAdmin\Models\StaffRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\SuperAdmin\Database\Factories\SuperAdminFactory;

class SuperAdmin extends FzStaff
{
  use HasFactory;

  protected $table = parent::TABLE_NAME;

  const DASHBOARD_ROUTE_PREFIX = 'super-admin';
  const ROUTE_NAME_PREFIX = 'superadmin.';


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
