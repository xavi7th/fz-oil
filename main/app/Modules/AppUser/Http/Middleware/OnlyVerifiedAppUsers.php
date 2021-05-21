<?php

namespace App\Modules\AppUser\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OnlyVerifiedAppUsers
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle(Request $request, Closure $next)
  {

    if (!$request->user()->is_otp_verified()) {
      return response()->json('Unverified user', 422);
    }

    return $next($request);
  }
}
