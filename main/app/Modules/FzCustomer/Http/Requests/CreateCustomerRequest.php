<?php

namespace App\Modules\FzCustomer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\FzCustomer\Models\FzCustomer;

class CreateCustomerRequest extends FormRequest
{

  public function rules()
  {
    if ($this->isMethod('POST')) {
      return [
        'email' => ['required', 'email', 'unique:fz_customers,email'],
        'full_name' => ['required', 'string'],
        'phone' => ['required', 'unique:fz_staff,phone'],
        'gender' => ['required'],
        'address' => ['required'],
      ];
    } else {
      return [
        'email' => ['required', 'email', 'unique:fz_customers,email,' . $this->customer->email . ',email'],
        'full_name' => ['required', 'string'],
        'phone' => ['required', 'unique:fz_staff,phone,' . $this->customer->phone . ',phone'],
        'gender' => ['required'],
        'address' => ['required'],
      ];
    }
  }

  public function createFzCustomer(): FzCustomer
  {
    return FzCustomer::create($this->validated());
  }

  public function updateFzCustomeDetails(): int
  {
    return $this->customer->update($this->validated());
  }

  public function authorize()
  {
    return true;
  }
}
