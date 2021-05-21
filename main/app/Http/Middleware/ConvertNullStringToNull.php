<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest;

class ConvertNullStringToNull extends TransformsRequest
{
  /**
   * Transform the given value.
   *
   * @param  string  $key
   * @param  mixed  $value
   * @return mixed
   */
  protected function transform($key, $value)
  {
    return is_string($value) && $value === 'null' ? null : $value;
  }
}
