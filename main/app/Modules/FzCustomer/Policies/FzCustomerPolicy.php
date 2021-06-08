<?php

namespace App\Modules\FzCustomer\Policies;

use App\Modules\FzCustomer\Models\FzCustomer;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FzCustomerPolicy
{
  use HandlesAuthorization;

  public function viewAny(User $user)
  {
    return $user->is_operative() ? $this->allow() : $this->deny('You cannot view registered customers accounts.');
  }
  public function view(User $user)
  {
    return $user->is_operative() ? $this->allow() : $this->deny('You cannot view this customer\'s details.');
  }
  public function create(User $user)
  {
    return $user->is_operative() && $user->isSalesRep() ? $this->allow() : $this->deny('You cannot create customers.');
  }

  public function update(User $user)
  {
    return $user->is_operative() && $user->isSupervisor() ? $this->allow() : $this->deny('You cannot update customers profile.');
  }

  public function delete(User $user)
  {
    return $user->is_operative() && $user->isSuperAdmin() ? $this->allow() : $this->deny('You cannot delete customers profile.');
  }

  public function restore(User $user)
  {
    return $user->is_operative() && $user->isSuperAdmin() ? $this->allow() : $this->deny('You cannot restore customers profile.');
  }

  public function suspend(User $user)
  {
    return $user->is_operative() && $user->isSupervisor() ? $this->allow() : $this->deny('You cannot suspend customers account.');
  }

  public function activate(User $user)
  {
    return $user->is_operative() && $user->isSupervisor() ? $this->allow() : $this->deny('You cannot activate customers account.');
  }

  public function setCreditLimit(User $user)
  {
    return $user->is_operative() && $user->isSupervisor() ? $this->allow() : $this->deny('You cannot set cutomer\'s credit limit.');
  }
}
