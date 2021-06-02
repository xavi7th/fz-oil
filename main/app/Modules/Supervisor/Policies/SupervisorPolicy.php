<?php

namespace App\Modules\Supervisor\Policies;

use App\User;
use App\Modules\Supervisor\Models\Supervisor;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupervisorPolicy
{
    use HandlesAuthorization;

   /**
   * Determine if the given supervisor access supervisor dashboard.
   *
   * @param  \App\Models\User  $user
   * @return bool
   */
  public function accessDashboard(User $user)
  {
    return $user->isSupervisor() ? $this->allow() : $this->deny('You cannot view registered supervisors accounts.');
  }

  /**
   * Determine if the given supervisor can be created by the user.
   *
   * @param  \App\Models\User  $user
   * @return bool
   */
  public function viewAny(User $user)
  {
      return $user->isSuperAdmin() ? $this->allow() : $this->deny('You cannot view registered supervisors accounts.');
  }
    /**
   * Determine if the given supervisor can be created by the user.
   *
   * @param  \App\Models\User  $user
   * @return bool
   */
  public function view(User $user, Supervisor $supervisor)
  {
      return $user->isSuperAdmin() ? $this->allow() : $this->deny('You cannot create this supervisor\'s details.');
  }
    /**
   * Determine if the given supervisor can be created by the user.
   *
   * @param  \App\Models\User  $user
   * @return bool
   */
  public function create(User $user)
  {
      return $user->isSuperAdmin() ? $this->allow() : $this->deny('You cannot create supervisors.');
  }

  /**
   * Determine if the given supervisor can be updated by the user.
   *
   * @param  \App\Models\User  $user
   * @return bool
   */
  public function update(User $user, Supervisor $supervisor)
  {
    return $user->isSuperAdmin() ? $this->allow() : $this->deny('You cannot update supervisors profile.');
  }

  /**
   * Determine if the given supervisor can be deleted by the user.
   *
   * @param  \App\Models\User  $user
   * @return bool
   */
  public function delete(User $user, Supervisor $supervisor)
  {
    return $user->isSuperAdmin() ? $this->allow() : $this->deny('You cannot delete supervisors profile.');
  }

  /**
   * Determine if the given supervisor can be suspended by the user.
   *
   * @param  \App\Models\User  $user
   * @return bool
   */
  public function suspend(User $user, Supervisor $supervisor)
  {
    return $user->isSuperAdmin() ? $this->allow() : $this->deny('You cannot suspend supervisors account.');
  }
  /**
   * Determine if the given supervisor can be restored by the user.
   *
   * @param  \App\Models\User  $user
   * @return bool
   */
  public function restore(User $user, Supervisor $supervisor)
  {
    return $user->isSuperAdmin() ? $this->allow() : $this->deny('You cannot restore supervisors profile.');
  }

  /**
   * Determine if the given supervisor can be activated by the user.
   *
   * @param  \App\Models\User  $user
   * @return bool
   */
  public function activate(User $user, Supervisor $supervisor)
  {
    return $user->isSuperAdmin() ? $this->allow() : $this->deny('You cannot activate supervisors account.');
  }
}
