<?php

namespace App\Modules\FzCustomer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\FzCustomer\Models\FzCustomer;
use App\Modules\FzCustomer\Models\CreditTransaction;

class CreateCustomerCreditRepaymentTransactionRequest extends FormRequest
{
  public function rules()
  {
    return [
      'fz_customer_id' => ['required'],
      'recorded_by' => ['required'],
      'trans_type' => ['required'],
      'amount' => ['required', function ($attribute, $value, $fail) {
        $value > ($this->customer->credit_limit - $this->customer->credit_balance) ? $fail('The customer\'s debt is not up to ' . $value) : null;
      }],
      'trans_date' => ['required'],
      'payment_type' => ['required'],
      'company_bank_account_id' => ['exclude_if:payment_type,cash', 'required', 'exists:company_bank_accounts,id'],
    ];
  }

  public function createRepaymentTransaction(): CreditTransaction
  {
    return $this->customer->createCreditRepaymentTransaction($this->amount, $this->recorded_by, $this->trans_date->format('Y-m-d'), $this->payment_type, $this->company_bank_account_id);
  }

  public function authorize()
  {
    return true;
  }
}
