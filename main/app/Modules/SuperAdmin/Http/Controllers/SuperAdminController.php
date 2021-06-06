<?php

namespace App\Modules\SuperAdmin\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use RachidLaasri\Travel\Travel;
use App\Http\Controllers\Controller;
use App\Modules\FzStaff\Services\MenuService;
use Illuminate\Support\Facades\Route;
use App\Modules\SuperAdmin\Models\SuperAdmin;

class SuperAdminController extends Controller
{

  static function routes()
  {
    Route::middleware(['auth:super_admin'])->prefix(SuperAdmin::DASHBOARD_ROUTE_PREFIX)->name(SuperAdmin::ROUTE_NAME_PREFIX)->group(function () {
      Route::get('', [self::class, 'index'])->name('dashboard');
    });
  }

  public function index(Request $request)
  {
    dd((new MenuService)->setUser($request->user())->setHeirarchical(true)->getRoutes());
    return Inertia::render('SuperAdmin::SuperAdminDashboard', self::getDashboardStatistics($request->records_date))->withViewData([
      'title' => 'Hello theEects',
      'metaDesc' => ' This page is ...'
    ]);
  }

  public function manageFzStaff(Request $request)
  {
    return Inertia::render('SuperAdmin::ManageStaff')->withViewData([
      'title' => 'Hello theEects',
      'metaDesc' => ' This page is ...'
    ]);
  }

  static function getDashboardStatistics(?string $date = null)
  {
    $date && Travel::to($date);

    // $sales_record_today = ProductSaleRecord::today()->get();

    return [
      'statistics' => fn () => [
        // 'total_daile_sale_count' => (int) $sales_record_today->count(),
      ]
    ];
  }
}
