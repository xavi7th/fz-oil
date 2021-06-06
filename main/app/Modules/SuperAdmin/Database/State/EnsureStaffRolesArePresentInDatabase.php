<?php

namespace App\Modules\SuperAdmin\Database\State;

use App\Modules\SuperAdmin\Models\StaffRole;
use DB;


class EnsureStaffRolesArePresentInDatabase
{
  public function __invoke()
  {
    if ($this->staff_roles_present()) {
      return;
    }

    StaffRole::factory()->count(4)->create();
  }

  public function staff_roles_present(): bool
  {
    return DB::table('staff_roles')->count() > 0;
  }
}
