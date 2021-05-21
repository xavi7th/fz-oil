<?php

namespace App\Modules\SuperAdmin\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateBankAccountRequest extends FormRequest
{

  public function rules()
  {
    if ($this->isMethod('PUT')) {
      return [
        'bank' =>   'required|string',
        'account_name' =>  'required|string',
        'account_number' =>  ['required', 'numeric', Rule::unique('company_bank_accounts')->ignore($this->route('companyBankAccount')->account_number, 'account_number')],
        'account_type' => 'string',
        'account_description' => 'nullable|string',
        'img' => 'image|mimes:jpeg,png,jpg,gif,svg',
      ];
    } elseif ($this->isMethod('POST')) {
      return [
        'bank' =>  'required|string',
        'account_name' =>  'required|string',
        'account_number' => 'required|numeric|unique:company_bank_accounts,account_number',
        'account_type' => 'required|string',
        'account_description' => 'nullable|string',
        'img' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
      ];
    }
  }

  public function authorize()
  {
    return auth('super_admin')->check();
  }
}
