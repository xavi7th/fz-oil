<?php

namespace App\Modules\Supervisor\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\FzCustomer\Models\FzCustomer;
use App\Modules\Supervisor\Models\Supervisor;
use App\Modules\FzStockManagement\Models\FzStock;
use App\Modules\OfficeExpense\Models\OfficeExpense;
use App\Modules\PurchaseOrder\Models\PurchaseOrder;
use App\Modules\FzStockManagement\Models\FzPriceBatch;
use DB;

class SupervisorController extends Controller
{
  static function routes()
  {
    Route::prefix(Supervisor::DASHBOARD_ROUTE_PREFIX)->name(Supervisor::ROUTE_NAME_PREFIX)->group(function () {
      Route::get('/', [self::class, 'dashboardPage'])->name('dashboard')->middleware('auth:supervisor')->defaults('menu', __e('Dashboard', 'accessDashboard,' . Supervisor::class, 'box', false));

      Supervisor::staffRoutes();
    });
  }

  public function dashboardPage(Request $request)
  {
    $this->authorize('accessDashboard', Supervisor::class);

    return Inertia::render('Supervisor::Dashboard',[
      'registered_customers_count' => FzCustomer::count(),
      'registered_sales_rep_count' => FzCustomer::count(),
      'available_oil_stock_count' => FzStock::oil()->sum('stock_quantity'),
      'available_gallon_stock_count' => FzStock::gallon()->sum('stock_quantity'),
      'price_batch_count' => FzPriceBatch::count(),
      'total_daily_expenses' => OfficeExpense::today()->sum('amount'),
      'total_purchase_orders_count' => PurchaseOrder::count(),
      'total_daily_purchase_order_count' => PurchaseOrder::today()->count(),
      'total_daily_purchase_order_amount' => PurchaseOrder::today()->sum('total_amount_paid'),
      'total_monthly_purchase_order_count' => PurchaseOrder::today()->count(),
      'total_monthly_purchase_order_amount' => PurchaseOrder::thisMonth()->sum('total_amount_paid'),
      'total_daily_profit' => PurchaseOrder::today()->get()->sum(fn ($rec) => $rec->total_amount_paid - $rec->total_cost_price ),
      'total_monthly_profit' => PurchaseOrder::thisMonth()->get()->sum(fn ($rec) => $rec->total_amount_paid - $rec->total_cost_price ),
    ]);
  }

}
