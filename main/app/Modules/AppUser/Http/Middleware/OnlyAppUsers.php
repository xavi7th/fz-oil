<?php

namespace App\Modules\AppUser\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Modules\AppUser\Models\AppUser;

class OnlyAppUsers
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
    if (!AppUser::canAccess()) {
      auth('app_user')->logout();
      try {
        auth('app_api_user')->logout();
      } catch (\Throwable $e) { }
      return response()->json(['status' => 'Forbbiden'], 403);
    }

    return $next($request);
  }
}
