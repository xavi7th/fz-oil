<?php

namespace App\Http\Middleware;

use Closure;

class NoApi
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    if ($request->isApi()) {
      return response()->json('Welcome to ' . config('app.name') . ' API service', 200);
    } else {
      return $next($request);
    }
  }
}
