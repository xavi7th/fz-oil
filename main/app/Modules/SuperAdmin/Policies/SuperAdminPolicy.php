<?php

namespace App\Modules\SuperAdmin\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SuperAdminPolicy
{
  use HandlesAuthorization;


  public function accessDashboard(User $user)
  {
    return $user->isSuperAdmin() ? $this->allow() : $this->deny('You cannot view view admin dashboard.');
  }
}
