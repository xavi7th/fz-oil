<?php

namespace App\Modules\FzStockManagement\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\FzStockManagement\Models\FzStock;
use App\Modules\FzStockManagement\Models\FzProductType;
use App\Modules\FzStockManagement\Models\FzProductBatch;

class FzStockManagementController extends Controller
{
  static function routes()
  {
    Route::middleware('web')->prefix(FzStock::DASHBOARD_ROUTE_PREFIX)->name(FzStock::ROUTE_NAME_PREFIX)->group(function () {
      Route::prefix(FzProductType::DASHBOARD_ROUTE_PREFIX)->name(FzProductType::ROUTE_NAME_PREFIX)->group(function () {
        Route::get('', [self::class, 'index'])->name('index');
      });
      // Route::prefix(FzProductBatch::DASHBOARD_ROUTE_PREFIX)->name(FzProductBatch::ROUTE_NAME_PREFIX)->group(function () {
      //   Route::get('', [self::class, 'getProductBatches'])->name('index');
      //   Route::get('trade-in', [self::class, 'manageSwapWithoutPurchase'])->name('trade-in');
      //   Route::get('{customer}/purchase-orders', [self::class, 'manageCustomerPurchaseOrder'])->name('customer.purchase_order');
      // });
    });
  }

  public function getProductBatches(Request $request)
  {
    return Inertia::render('FzStockManagement::ManageProductBatches')->withViewData([
      'title' => 'Hello theEects',
      'metaDesc' => ' This page is ...'
    ]);
  }

  public function manageSwapWithoutPurchase(Request $request)
  {
    return Inertia::render('FzStockManagement::ManageTradeIns')->withViewData([
      'title' => 'Hello theEects',
      'metaDesc' => ' This page is ...'
    ]);
  }

  public function manageCustomerPurchaseOrder(Request $request)
  {
    return Inertia::render('FzStockManagement::ManageCustomerPurchaseOrder')->withViewData([
      'title' => 'Hello theEects',
      'metaDesc' => ' This page is ...'
    ]);
  }

}
