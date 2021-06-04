<?php

namespace App\Modules\FzCustomer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\FzCustomer\Models\FzCustomer;

class CreateCustomerRequest extends FormRequest
{

  public function rules()
  {
    return [
      'email' => ['required', 'email'],
      'full_name' => ['required', 'string'],
      'phone' => ['required', 'unique:fz_staff,phone'],
      'gender' => ['required'],
      'address' => ['required'],
    ];
  }

  public function createFzCustomer(): FzCustomer
  {
    return FzCustomer::create($this->validated());
  }

  public function authorize()
  {
    return true;
  }
}
