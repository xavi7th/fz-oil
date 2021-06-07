<?php

namespace App\Modules\SuperAdmin\Http\Requests;

use App\User;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class CreateStaffRequest extends FormRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    if ($this->isMethod('PUT')) {
      return [
        'email' => ['required', 'email', 'unique:fz_staff,email,'.$this->staff->email.',email'],
        'user_name' => ['required', 'string'],
        'full_name' => ['required', 'string'],
        'phone' => ['required', 'unique:fz_staff,phone,'.$this->staff->phone.',phone'],
        'gender' => ['required'],
        'address' => ['required'],
        'avatar' => '',
        'staff_role_id' => ['required', 'exists:staff_roles,id'],
      ];
    }
    elseif ($this->isMethod('POST')) {
      return [
        'email' => ['required', 'email', 'unique:fz_staff,email'],
        'user_name' => ['required', 'string'],
        // 'password' => ['required', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()],
        'full_name' => ['required', 'string'],
        'phone' => ['required', 'unique:fz_staff,phone'],
        'gender' => ['required'],
        'address' => ['required'],
        'avatar' => '',
        'staff_role_id' => ['required', 'exists:staff_roles,id'],
      ];
    }
  }

  public function createStaff($userClass): User
  {
    return $userClass::create($this->validated());
  }

  public function updateStaff(User $staff): int
  {
    return $staff->update($this->validated());
  }

  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }


  public function validated()
  {
    $id_url = null;
    if ($this->hasFile('avatar')) {
      $id_url = compress_image_upload('avatar', 'user-avatars/', 'user-avatars/thumbs/', 1400, true, 50)['avatar'];
    }

    return array_merge(parent::validated(), ['id_url' => $id_url, 'password' => 'pass']);
  }
}
