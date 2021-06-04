<?php

namespace App\Modules\PurchaseOrder\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Modules\PurchaseOrder\Models\PurchaseOrder;

class PurchaseOrderPolicy
{
  use HandlesAuthorization;

  /**
   * Determine if the given purchase_order can be created by the user.
   *
   * @param  \App\Models\User  $user
   * @return bool
   */
  public function viewAny(User $user)
  {
    return $user->is_operative() ? $this->allow() : $this->deny('You cannot view registered sales reps accounts.');
  }

  public function view(User $user, PurchaseOrder $purchase_order)
  {
    return $user->is_operative() ? $this->allow() : $this->deny('You cannot create this sales rep\'s details.');
  }

  public function create(User $user)
  {
    return $user->isSalesRep() ? $this->allow() : $this->deny('You cannot create sales reps.');
  }

}
