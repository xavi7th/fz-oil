<?php

namespace App\Modules\SalesRep\Policies;

use App\User;
use App\Modules\SalesRep\Models\SalesRep;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalesRepPolicy
{
  use HandlesAuthorization;

  public function accessDashboard(User $user)
  {
    return $user->is_operative() && $user->isSalesRep() ? $this->allow() : $this->deny('You cannot view registered supervisors accounts.');
  }

  /**
   * Determine if the given sales_rep can be created by the user.
   *
   * @param  \App\Models\User  $user
   * @return bool
   */
  public function viewAny(User $user)
  {
    return $user->is_operative() && ($user->isSuperAdmin() || $user->isSupervisor()) ? $this->allow() : $this->deny('You cannot view registered sales reps accounts.');
  }
    /**
   * Determine if the given sales_rep can be created by the user.
   *
   * @param  \App\Models\User  $user
   * @return bool
   */
  public function view(User $user)
  {
    return $user->is_operative() && ($user->isSuperAdmin() || $user->isSupervisor()) ? $this->allow() : $this->deny('You cannot create this sales rep\'s details.');
  }
    /**
   * Determine if the given sales_rep can be created by the user.
   *
   * @param  \App\Models\User  $user
   * @return bool
   */
  public function create(User $user)
  {
    return $user->is_operative() && ($user->isSuperAdmin() || $user->isSupervisor()) ? $this->allow() : $this->deny('You cannot create sales reps.');
  }

  /**
   * Determine if the given supervisor can be updated by the user.
   *
   * @param  \App\Models\User  $user
   * @return bool
   */
  public function update(User $user)
  {
    return $user->is_operative() && ($user->isSuperAdmin() || $user->isSupervisor()) ? $this->allow() : $this->deny('You cannot update sales reps profile.');
  }

  /**
   * Determine if the given supervisor can be deleted by the user.
   *
   * @param  \App\Models\User  $user
   * @return bool
   */
  public function delete(User $user)
  {
    return $user->is_operative() && $user->isSuperAdmin() ? $this->allow() : $this->deny('You cannot delete sales reps profile.');
  }

  /**
   * Determine if the given supervisor can be suspended by the user.
   *
   * @param  \App\Models\User  $user
   * @return bool
   */
  public function suspend(User $user)
  {
    return $user->is_operative() && ($user->isSuperAdmin() || $user->isSupervisor()) ? $this->allow() : $this->deny('You cannot suspend sales reps account.');
  }
  /**
   * Determine if the given supervisor can be restored by the user.
   *
   * @param  \App\Models\User  $user
   * @return bool
   */
  public function restore(User $user)
  {
    return $user->is_operative() && $user->isSuperAdmin() ? $this->allow() : $this->deny('You cannot restore sales reps profile.');
  }

  /**
   * Determine if the given supervisor can be activated by the user.
   *
   * @param  \App\Models\User  $user
   * @return bool
   */
  public function activate(User $user)
  {
    return $user->is_operative() && ($user->isSuperAdmin() || $user->isSupervisor()) ? $this->allow() : $this->deny('You cannot activate sales reps account.');
  }
}
