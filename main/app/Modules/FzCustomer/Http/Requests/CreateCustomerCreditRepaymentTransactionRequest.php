<?php

namespace App\Modules\FzCustomer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\FzCustomer\Models\CreditTransaction;
use Carbon\Carbon;

class CreateCustomerCreditRepaymentTransactionRequest extends FormRequest
{
  public function rules()
  {
    return [
      'amount' => ['required', 'numeric', function ($attribute, $value, $fail) {
        $value > ($this->customer->credit_limit - $this->customer->credit_balance) ? $fail('The customer\'s debt is not up to ' . $value) : null;
      }],
      'trans_date' => ['required', 'date'],
      'payment_type' => ['required', 'in:cash,bank'],
      'company_bank_account_id' => ['exclude_if:payment_type,cash', 'required', 'exists:company_bank_accounts,id'],
    ];
  }

  public function createRepaymentTransaction(): CreditTransaction
  {
    $this->customer->addToCreditBalance($this->amount);
    return $this->customer->createCreditRepaymentTransaction($this->amount, $this->user()->id, Carbon::parse($this->trans_date)->toDateString(), $this->payment_type, $this->company_bank_account_id);
  }

  public function authorize()
  {
    return true;
  }
}
