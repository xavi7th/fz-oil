<?php

namespace App\Modules\CompanyBankAccount\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\CompanyBankAccount\Models\CompanyBankAccount;

class CreateCompanyBankAccountRequest extends FormRequest
{

  public function rules()
  {
    if ($this->isMethod('POST')) {
      return [
        'account_name' => ['required'],
        'account_number' => ['required', 'numeric', 'unique:company_bank_accounts,account_number'],
        'bank_name' => ['required'],
      ];
    }
    elseif ($this->isMethod('PUT')) {
      return [
        'account_name' => ['required'],
        'account_number' => ['required', 'numeric', 'unique:company_bank_accounts,account_number,'.$this->bank_account->account_number.',account_number'],
        'bank_name' => ['required'],
      ];
    }
  }

  public function createAccount(): CompanyBankAccount
  {
    return CompanyBankAccount::create($this->validated());
  }

  public function updateAccount(): bool
  {
    return $this->bank_account->update($this->validated());
  }

  public function authorize()
  {
    return true;
  }
}
