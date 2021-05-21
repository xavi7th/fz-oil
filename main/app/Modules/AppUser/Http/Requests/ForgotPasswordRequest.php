<?php

namespace App\Modules\AppUser\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class ForgotPasswordRequest extends FormRequest
{

  public function rules()
  {
    return [
      'email' => 'required|email|exists:app_users,email',
    ];
  }

  public function authorize()
  {
    return true;
  }

  public function messages()
  {
    return [
      'email.exists' => 'If your email exists we will send you a reset code',
    ];
  }

}
