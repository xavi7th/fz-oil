<?php

namespace App\Modules\Supervisor\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Supervisor\Models\Supervisor;

class CreateSupervisorRequest extends FormRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'email' => ['required', 'email'],
      'user_name' => ['required', 'string'],
      'password' => ['required', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()],
      'full_name' => ['required', 'string'],
      'phone' => ['required', 'unique:fz_staff,phone'],
      'gender' => ['required'],
      'address' => ['required'],
      'id_url' => '',
      'staff_role_id' => ['required', 'exists:staff_roles,id'],
    ];
  }

  public function createSupervisor(): Supervisor
  {
    return Supervisor::create($this->validated());
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


  // public function validated()
  // {
  //   return (array_merge(parent::validated(), ['is_active'];
  // }
}
