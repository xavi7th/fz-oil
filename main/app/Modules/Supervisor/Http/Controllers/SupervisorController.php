<?php

namespace App\Modules\Supervisor\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\Supervisor\Models\Supervisor;

class SupervisorController extends Controller
{
  static function routes()
  {
    Route::prefix(Supervisor::DASHBOARD_ROUTE_PREFIX)->name(Supervisor::ROUTE_NAME_PREFIX)->group(function () {
      Route::get('/', [self::class, 'dashboardPage'])->name('dashboard')->middleware('auth:supervisor')->defaults('menu', __e('Dashboard', 'accessDashboard,' . Supervisor::class, 'box', false));

      Supervisor::staffRoutes();
    });
  }

  public function index(Request $request)
  {
    dd('here');
    $this->authorize('accessDashboard', Supervisor::class);

    return Inertia::render('Supervisor::Dashboard');
  }

}
