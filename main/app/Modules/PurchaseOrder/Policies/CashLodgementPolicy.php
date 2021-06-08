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
    return $user->is_operative() ? $this->allow() : $this->deny('You cannot view cash lodgements.');
  }

  public function view(User $user)
  {
    return $user->is_operative() ? $this->allow() : $this->deny('You cannot create this cash lodgement\'s details.');
  }

  public function create(User $user)
  {
    return $user->is_operative() && $user->isSalesRep() ? $this->allow() : $this->deny('You cannot create cash lodgements.');
  }
}
