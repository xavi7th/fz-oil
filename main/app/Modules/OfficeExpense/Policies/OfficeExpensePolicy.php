<?php

namespace App\Modules\OfficeExpense\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Modules\OfficeExpense\Models\OfficeExpense;

class OfficeExpensePolicy
{
  use HandlesAuthorization;

  public function viewAny(User $user)
  {
    return $user->is_operative() ? $this->allow() : $this->deny('You cannot view registered office expenses.');
  }

  public function view(User $user, OfficeExpense $purchase_order)
  {
    return $user->is_operative() ? $this->allow() : $this->deny('You cannot create this office expense\'s details.');
  }

  public function create(User $user)
  {
    return $user->is_operative() && $user->isSalesRep() ? $this->allow() : $this->deny('You cannot create office expenses.');
  }

  public function createPurchaseOrder(User $user)
  {
    return $user->is_operative() && $user->isSalesRep() ? $this->allow() : $this->deny('You cannot create office expenses.');
  }
}
