<?php

namespace App\Modules\FzCustomer\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Modules\FzCustomer\Models\CreditTransaction;

class CreditTransactionPolicy
{
  use HandlesAuthorization;

  public function viewAny(User $user)
  {
    return $user->is_operative() ? $this->allow() : $this->deny('You cannot view credit transactions.');
  }

  public function view(User $user, CreditTransaction $credit_transaction)
  {
    return $user->is_operative() ? $this->allow() : $this->deny('You cannot create this credit transaction\'s details.');
  }

  public function create(User $user)
  {
    return $user->is_operative() && $user->isSalesRep() ? $this->allow() : $this->deny('You cannot create credit transactions.');
  }

  public function createPurchaseOrder(User $user)
  {
    return $user->is_operative() && $user->isSalesRep() ? $this->allow() : $this->deny('You cannot create credit transactions.');
  }
}
