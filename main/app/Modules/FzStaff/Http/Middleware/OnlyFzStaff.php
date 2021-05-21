<?php

namespace App\Modules\FzStaff\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Modules\FzStaff\Models\FzStaff;

class OnlyFzStaff
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
    if (!FzStaff::canAccess()) {
      auth('fz_staff')->logout();
      return response()->json(['status' => 'Forbbiden'], 403);
    }

    return $next($request);
  }
}
