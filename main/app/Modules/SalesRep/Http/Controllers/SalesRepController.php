<?php

namespace App\Modules\SalesRep\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\SalesRep\Models\SalesRep;

class SalesRepController extends Controller
{
  static function routes()
  {
    Route::prefix(SalesRep::DASHBOARD_ROUTE_PREFIX)->name(SalesRep::ROUTE_NAME_PREFIX)->group(function () {
      Route::get('/', [self::class, 'index'])->name('dashboard')->middleware('auth:sales_rep');

      SalesRep::staffRoutes();
    });
  }

  public function index(Request $request)
  {
    $this->authorize('accessDashboard', SalesRep::class);

    return Inertia::render('SalesRep::Dashboard');
  }
}
