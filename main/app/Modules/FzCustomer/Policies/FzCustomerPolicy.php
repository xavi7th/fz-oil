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
  public function view(User $user, FzCustomer $fz_customer)
  {
    return $user->is_operative() ? $this->allow() : $this->deny('You cannot view this customer\'s details.');
  }
  public function create(User $user)
  {
      return $user->isSalesRep() ? $this->allow() : $this->deny('You cannot create customers.');
  }

  public function update(User $user, FzCustomer $fz_customer)
  {
    return $user->isSupervisor() ? $this->allow() : $this->deny('You cannot update customers profile.');
  }

  public function delete(User $user, FzCustomer $fz_customer)
  {
    return $user->isSuperAdmin() ? $this->allow() : $this->deny('You cannot delete customers profile.');
  }

  public function restore(User $user, FzCustomer $fz_customer)
  {
    return $user->isSuperAdmin() ? $this->allow() : $this->deny('You cannot restore customers profile.');
  }

  public function suspend(User $user, FzCustomer $fz_customer)
  {
    return $user->isSuperAdmin() ? $this->allow() : $this->deny('You cannot suspend customers account.');
  }

  public function activate(User $user, FzCustomer $fz_customer)
  {
    return $user->isSuperAdmin() ? $this->allow() : $this->deny('You cannot activate customers account.');
  }

  public function setCreditLimit(User $user, FzCustomer $fzCustomer)
  {
    return $user->isSuperAdmin() ? $this->allow() : $this->deny('You cannot set cutomer\'s credit limit.');
  }
}
