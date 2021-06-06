<?php

namespace App\Modules\CompanyBankAccount\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Modules\CompanyBankAccount\Models\CompanyBankAccount;

class CompanyBankAccountPolicy
{
  use HandlesAuthorization;

  public function viewAny(User $user)
  {
    return $user->is_operative() && ($user->isSuperAdmin() || $user->isSupervisor()) ? $this->allow() : $this->deny('You cannot view registered company bank accounts.');
  }

  public function view(User $user, CompanyBankAccount $company_bank_account)
  {
    return $user->is_operative() && ($user->isSuperAdmin() || $user->isSupervisor()) ? $this->allow() : $this->deny('You cannot create this company bank account\'s details.');
  }

  public function create(User $user)
  {
    return $user->is_operative() && $user->isSuperAdmin() ? $this->allow() : $this->deny('You cannot create company bank accounts.');
  }

  public function suspend(User $user)
  {
    return $user->is_operative() && $user->isSuperAdmin() ? $this->allow() : $this->deny('You cannot create company bank accounts.');
  }

  public function activate(User $user)
  {
    return $user->is_operative() && $user->isSuperAdmin() ? $this->allow() : $this->deny('You cannot create company bank accounts.');
  }
}
