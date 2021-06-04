<?php

namespace App\Modules\PurchaseOrder\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Modules\PurchaseOrder\Models\PurchaseOrder;

class PurchaseOrderPolicy
{
  use HandlesAuthorization;

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
    return $user->is_operative() && $user->isSalesRep() ? $this->allow() : $this->deny('You cannot create sales reps.');
  }

  public function createPurchaseOrder(User $user)
  {
    return $user->is_operative() && $user->isSalesRep() ? $this->allow() : $this->deny('You cannot create sales reps.');
  }

}
