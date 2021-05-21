<?php

namespace App\Modules\FzStaff\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class EditFzStaffProfileRequest extends FormRequest
{

  public function rules()
  {
    return [
      'email' => ['filled', 'email', Rule::unique('users')->ignore($this->user()->id)],
      'name' => 'filled|string',
      'password' => 'nullable|min:6|regex:/^([0-9a-zA-Z-\.\@]+)$/'
    ];
  }

  public function authorize()
  {
    return true;
  }
}
