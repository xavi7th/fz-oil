<?php

namespace App\Modules\FzStaff\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class ForgotPasswordRequest extends FormRequest
{

  public function rules()
  {
    return [
      'email' => 'required|email|exists:fz_staff,email',
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
