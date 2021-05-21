<?php

namespace App\Modules\AppUser\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class LoginRequest extends FormRequest
{

  public function rules()
  {
    return [

      'email' => 'required|email|exists:app_users,email',
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
