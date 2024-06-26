<?php

namespace App\Modules\FzStaff\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{

  public function rules()
  {
    return [
      'email' => 'required|email|exists:fz_staff,email',
      'token' => 'required|numeric',
      'password' => 'required|confirmed|min:8',
    ];
  }


  public function authorize()
  {
    return true;
  }
}
