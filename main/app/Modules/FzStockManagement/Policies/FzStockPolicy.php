<?php

namespace App\Modules\FzStockManagement\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FzStockPolicy
{
  use HandlesAuthorization;

  public function viewAny(User $user)
  {
    return $user->is_operative() ? $this->allow() : $this->deny('You cannot view stock.');
  }

  public function view(User $user)
  {
    return $user->is_operative() ? $this->allow() : $this->deny('You cannot create this stock\'s details.');
  }

  public function create(User $user)
  {
    return $user->is_operative() && $user->isSupervisor() ? $this->allow() : $this->deny('You cannot create stock items.');
  }

  public function update(User $user)
  {
    return $user->is_operative() && $user->isSupervisor() ? $this->allow() : $this->deny('You cannot update stock items.');
  }
}
