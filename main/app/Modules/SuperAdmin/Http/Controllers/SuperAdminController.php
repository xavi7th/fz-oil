<?php

namespace App\Modules\SuperAdmin\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use RachidLaasri\Travel\Travel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\SuperAdmin\Models\SuperAdmin;

class SuperAdminController extends Controller
{

  static function routes()
  {
    Route::middleware(['web'])->prefix(SuperAdmin::DASHBOARD_ROUTE_PREFIX)->name(SuperAdmin::ROUTE_NAME_PREFIX)->group(function () {
      Route::get('', [self::class, 'index'])->name('dashnoard');
    });
  }

  public function index(Request $request)
  {
    return Inertia::render('SuperAdmin,SuperAdminDashboard', self::getDashboardStatistics($request->records_date))->withViewData([
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
