<?php

namespace App\Modules\AppUser\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Contracts\Validation\Validator;


class AppUserUpdateProfileRequest extends FormRequest
{

  public function rules()
  {
    return [
      'first_name' => 'string|max:50',
      'last_name' => 'string|max:50',
      'password' => 'string|min:8',
      'bvn' => 'numeric|digits_between:11,16',
      'school' => 'string|max:50',
      'department' => 'string|max:50',
      'level' => 'numeric|digits_between:1,10',
      'mat_no' => [
        'string',
        'max:20',
        Rule::unique('app_users')->ignore(auth()->user()),
      ],
      'phone' => [
        'regex:/^[\+]?[0-9\Q()\E\s-]+$/i',
        Rule::unique('app_users')->ignore(auth()->user()),
      ],
    ];
  }

  public function authorize()
  {
    return true;
  }

}
