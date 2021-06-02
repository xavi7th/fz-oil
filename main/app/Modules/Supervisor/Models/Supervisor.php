<?php

namespace App\Modules\Supervisor\Models;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\SuperAdmin\Models\StaffRole;
use App\Modules\SuperAdmin\Traits\IsAStaff;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Supervisor\Database\Factories\SupervisorFactory;

class Supervisor extends User
{
  use HasFactory, IsAStaff;

  protected $table = parent::TABLE_NAME;

  const DASHBOARD_ROUTE_PREFIX = 'supervisors';
  const ROUTE_NAME_PREFIX = 'supervisor.';

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
