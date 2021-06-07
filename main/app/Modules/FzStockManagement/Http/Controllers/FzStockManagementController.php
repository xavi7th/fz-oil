<?php

namespace App\Modules\FzStockManagement\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\FzStockManagement\Models\FzStock;
use App\Modules\FzStockManagement\Models\FzProductType;
use App\Modules\SuperAdmin\Traits\AccessibleToAllStaff;

class FzStockManagementController extends Controller
{
  use AccessibleToAllStaff;

  static function routes()
  {
    Route::prefix(FzStock::DASHBOARD_ROUTE_PREFIX)->name(FzStock::ROUTE_NAME_PREFIX)->group(function () {
      Route::prefix(FzProductType::DASHBOARD_ROUTE_PREFIX)->name(FzProductType::ROUTE_NAME_PREFIX)->group(function () {
        Route::get('', [self::class, 'index'])->name('index')->defaults('menu', __e('Manage Stock', 'viewAny,' . FzStock::class, 'box', false));
      });
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
