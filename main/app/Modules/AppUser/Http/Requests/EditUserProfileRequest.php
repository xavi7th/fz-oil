<?php

namespace App\Modules\AppUser\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class EditUserProfileRequest extends FormRequest
{

  public function rules()
  {
    return [
      'email' => ['filled', 'email', Rule::unique('users')->ignore(Auth::appuser()->id)],
      'name' => 'filled|string',
      'password' => 'nullable|min:6|regex:/^([0-9a-zA-Z-\.\@]+)$/'
    ];
  }

  public function authorize()
  {
    return true;
  }
}
