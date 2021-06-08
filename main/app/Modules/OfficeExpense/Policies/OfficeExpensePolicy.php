<?php

namespace App\Modules\OfficeExpense\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OfficeExpensePolicy
{
  use HandlesAuthorization;

  public function viewAny(User $user)
  {
    return $user->is_operative() && ($user->isSuperAdmin() || $user->isSupervisor()) ? $this->allow() : $this->deny('You are not authorized to view office expenses.');
  }

  public function view(User $user)
  {
    return $user->is_operative() && ($user->isSuperAdmin() || $user->isSupervisor()) ? $this->allow() : $this->deny('You cannot create this office expense\'s details.');
  }

  public function create(User $user)
  {
    return $user->is_operative() && $user->isSupervisor() ? $this->allow() : $this->deny('You cannot create office expenses.');
  }
}
