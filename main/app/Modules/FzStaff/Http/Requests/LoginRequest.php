<?php

namespace App\Modules\FzStaff\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class LoginRequest extends FormRequest
{

  public function rules()
  {
    return [

      'email' => 'required|email|exists:fz_staff,email',
      'password' => 'required|string',
    ];
  }

  public function authorize()
  {
    return true;
  }

  public function messages()
  {
    return [
      'email.exists' => 'Invalid details provided',
    ];
  }

}
