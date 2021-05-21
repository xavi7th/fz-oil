<?php

namespace App\Modules\PublicPages\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
{

  public function rules()
  {
    return [
      'email' => 'required|email|max:100',
      'name' => 'required|max:255|string',
      'msg' => 'required|string',
    ];
  }

  public function authorize()
  {
    return true;
  }

  public function messages()
  {
    return [
      'email.required' => 'Your email cannot be empty',
      'email.email' => 'The email you supplied is invalid',
      'name.required' => 'Your name is required',
      'msg.required' => 'Tell us what you want to talk about',
    ];
  }
}
