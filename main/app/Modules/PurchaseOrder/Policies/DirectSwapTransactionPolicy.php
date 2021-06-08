<?php

namespace App\Modules\PurchaseOrder\Policies;

use App\Modules\PurchaseOrder\Models\DirectSwapTransaction;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DirectSwapTransactionPolicy
{
  use HandlesAuthorization;

  public function viewAny(User $user)
  {
    return $user->is_operative() ? $this->allow() : $this->deny('You cannot view direct trade in transactions.');
  }

  public function view(User $user)
  {
    return $user->is_operative() ? $this->allow() : $this->deny('You cannot create this direct trade in transaction\'s details.');
  }

  public function create(User $user)
  {
    return $user->is_operative() && $user->isSalesRep() ? $this->allow() : $this->deny('You cannot create direct trade in transactions.');
  }
}
