<?php

namespace App\Modules\SalesRep\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\SalesRep\Models\SalesRep;
use App\Modules\FzCustomer\Models\FzCustomer;
use App\Modules\FzStockManagement\Models\FzPriceBatch;
use App\Modules\FzStockManagement\Models\FzStock;
use App\Modules\PurchaseOrder\Models\PurchaseOrder;

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

    return Inertia::render('SalesRep::Dashboard', [
      'registered_customers_count' => FzCustomer::count(),
      'available_oil_stock_count' => FzStock::oil()->sum('stock_quantity'),
      'available_gallon_stock_count' => FzStock::gallon()->sum('stock_quantity'),
      'price_batch_count' => FzPriceBatch::count(),
      'total_purchase_orders_count' => PurchaseOrder::count(),
      'total_daily_purchase_order_count' => PurchaseOrder::today()->count(),
      'sales_rep_daily_purchase_order_count' => $request->user()->purchase_orders()->today()->count(),
    ]);
  }
}
