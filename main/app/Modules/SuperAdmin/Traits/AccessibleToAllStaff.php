<?php

namespace App\Modules\SuperAdmin\Traits;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


trait AccessibleToAllStaff
{
  use AuthorizesRequests;

  public function __construct()
  {
    $this->middleware('auth:' . collect(config('auth.guards'))->keys()->implode(','))->except('auth.logout');
  }
}
