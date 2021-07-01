<?php

namespace App\Modules\SuperAdmin\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use RachidLaasri\Travel\Travel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\FzCustomer\Models\FzCustomer;
use App\Modules\SuperAdmin\Models\SuperAdmin;
use App\Modules\FzStockManagement\Models\FzStock;
use App\Modules\OfficeExpense\Models\OfficeExpense;
use App\Modules\PurchaseOrder\Models\PurchaseOrder;
use App\Modules\FzStockManagement\Models\FzPriceBatch;
use App\Modules\SalesRep\Models\SalesRep;
use App\Modules\Supervisor\Models\Supervisor;

class SuperAdminController extends Controller
{

  static function routes()
  {
    Route::middleware(['auth:super_admin'])->prefix(SuperAdmin::DASHBOARD_ROUTE_PREFIX)->name(SuperAdmin::ROUTE_NAME_PREFIX)->group(function () {
      Route::get('', [self::class, 'index'])->name('dashboard')->defaults('menu', __e('Dashboard', 'accessDashboard,' . SuperAdmin::class, 'box', false));
    });
  }

  public function index(Request $request)
  {
    $this->authorize('accessDashboard', SuperAdmin::class);
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
    // $date && Travel::to($date);

    return [
      'registered_customers_count' => FzCustomer::count(),
      'registered_sales_rep_count' => SalesRep::count(),
      'registered_supervisors_count' => Supervisor::count(),
      'available_oil_stock_count' => FzStock::oil()->sum('stock_quantity'),
      'available_gallon_stock_count' => FzStock::gallon()->sum('stock_quantity'),
      'price_batch_count' => FzPriceBatch::count(),
      'total_daily_expenses' => OfficeExpense::today()->sum('amount'),
      'total_purchase_orders_count' => PurchaseOrder::count(),
      'total_daily_purchase_order_count' => PurchaseOrder::today()->count(),
      'total_daily_purchase_order_amount' => PurchaseOrder::today()->sum('total_amount_paid'),
      'total_monthly_purchase_order_count' => PurchaseOrder::today()->count(),
      'total_monthly_purchase_order_amount' => PurchaseOrder::thisMonth()->sum('total_amount_paid'),
      'total_daily_profit' => PurchaseOrder::today()->get()->sum(fn ($rec) => $rec->total_amount_paid + ($rec->swap_value * $rec->swap_quantity) - ($rec->price_batch->cost_price * $rec->purchased_quantity) ),
      'total_monthly_profit' => PurchaseOrder::thisMonth()->get()->sum(fn ($rec) => $rec->total_amount_paid + ($rec->swap_value * $rec->swap_quantity) - ($rec->price_batch->cost_price * $rec->purchased_quantity) ),
    ];
  }
}
