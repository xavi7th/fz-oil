<?php

namespace App\Http\Middleware;

use Closure;

class CheckForInactivity
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
    /**
     * Get the current idle time in seconds
     */
    if ($request->session()->has('LAST_ACTIVITY')) {
      $idletime = now()->diffInSeconds(session()->get('LAST_ACTIVITY'));
    } else {
      /**
       * Initialise idle time to 120 seconds
       * ? Assume time for round trip web request is an average of 120 seconds
       */
      $idletime = 10;
    }

    /**
     * Set the idle time in a session
     */
    // session(['IDLE_TIME' => $idletime]);

    /**
     * Set now as the time of last activity
     */
    session()->put('LAST_ACTIVITY', now());

    /**
     * ! Log the user out if idle for more that specified time
     */
    if ($idletime > config('app.permissible_idle_time')) {
      foreach (array_keys(config('auth.guards')) as $guard) {
        auth()->guard($guard)->logout();
      }
    }

    return $next($request);
  }
}
