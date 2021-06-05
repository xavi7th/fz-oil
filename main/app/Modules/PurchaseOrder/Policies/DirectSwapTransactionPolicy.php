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
    return $user->is_operative() ? $this->allow() : $this->deny('You cannot view swap transactions.');
  }

  public function view(User $user, DirectSwapTransaction $swap_transaction)
  {
    return $user->is_operative() ? $this->allow() : $this->deny('You cannot create this swap transaction\'s details.');
  }

  public function create(User $user)
  {
    return $user->is_operative() && $user->isSalesRep() ? $this->allow() : $this->deny('You cannot create swap transactions.');
  }
}
