<?php

namespace App\Modules\FzStaff\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\FzStaff\Models\FzStaff;

class FzStaffController extends Controller
{
  static function routes()
  {
    // Route::middleware(['web'])->prefix(FzStaff::DASHBOARD_ROUTE_PREFIX)->name(FzStaff::ROUTE_NAME_PREFIX)->group(function () {
    //     Route::get('/', [self::class, 'index'])->name('fzstaff.dashboard');
    // });
  }

  // public function index(Request $request)
  // {
  //   return Inertia::render('FzStaff::UserDashboard');
  // }
}
