<?php

namespace App\Modules\PurchaseOrder\Policies;

use App\Modules\PurchaseOrder\Models\CashLodgement;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CashLodgementPolicy
{
  use HandlesAuthorization;

  public function viewAny(User $user)
  {
    return $user->is_operative() ? $this->allow() : $this->deny('You cannot view registered sales reps accounts.');
  }

  public function view(User $user, CashLodgement $cash_lodgement)
  {
    return $user->is_operative() ? $this->allow() : $this->deny('You cannot create this sales rep\'s details.');
  }

  public function create(User $user)
  {
    return $user->is_operative() && $user->isSalesRep() ? $this->allow() : $this->deny('You cannot create sales reps.');
  }
}
