<?php

namespace App\Modules\AppUser\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\AppUser\Models\AppUser;

class AppUserController extends Controller
{
  static function routes()
  {

    Route::middleware(['web'])->prefix(AppUser::DASHBOARD_ROUTE_PREFIX)->name(AppUser::ROUTE_NAME_PREFIX)->group(function () {
        Route::get('/', [self::class, 'index'])->name('appuser.dashboard');
    });
  }

  public function index(Request $request)
  {
    return Inertia::render('AppUser,UserDashboard');
  }
}
