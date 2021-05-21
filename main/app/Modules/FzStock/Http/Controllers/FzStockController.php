<?php

namespace App\Modules\FzStock\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\FzStock\Models\FzStock;
use App\Modules\FzStock\Models\FzProductType;
use App\Modules\FzStock\Models\FzProductBatch;
use App\Modules\FzStock\Models\FzProductPriceBatch;

class FzStockController extends Controller
{
  static function routes()
  {
    Route::middleware('web')->prefix(FzStock::DASHBOARD_ROUTE_PREFIX)->name(FzStock::ROUTE_NAME_PREFIX)->group(function () {
      Route::prefix(FzProductType::DASHBOARD_ROUTE_PREFIX)->name(FzProductType::ROUTE_NAME_PREFIX)->group(function () {
        Route::get('', [self::class, 'index'])->name('index');
      });
      Route::prefix(FzProductBatch::DASHBOARD_ROUTE_PREFIX)->name(FzProductBatch::ROUTE_NAME_PREFIX)->group(function () {
        Route::get('', [self::class, 'getProductBatches'])->name('index');
      });
    });
  }

  public function getProductBatches(Request $request)
  {
    return Inertia::render('FzStock::ManageProductBatches')->withViewData([
      'title' => 'Hello theEects',
      'metaDesc' => ' This page is ...'
    ]);
  }

}
