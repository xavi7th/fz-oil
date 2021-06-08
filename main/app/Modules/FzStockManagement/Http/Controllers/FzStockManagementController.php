<?php

namespace App\Modules\FzStockManagement\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use App\Modules\FzStockManagement\Models\FzStock;
use App\Modules\FzStockManagement\Models\FzPriceBatch;
use App\Modules\FzStockManagement\Models\FzProductType;
use App\Modules\SuperAdmin\Traits\AccessibleToAllStaff;
use App\Modules\FzStockManagement\Http\Requests\CreateFzStockRequest;

class FzStockManagementController extends Controller
{
  use AccessibleToAllStaff;

  static function routes()
  {
    Route::prefix(FzStock::DASHBOARD_ROUTE_PREFIX)->name(FzStock::ROUTE_NAME_PREFIX)->group(function () {
      Route::get('create', [self::class, 'getStockList'])->name('list')->defaults('menu', __e('Manage Stock', 'viewAny,' . FzStock::class, 'box', false));
      Route::post('create', [self::class, 'createStock'])->name('create');
      Route::put('{fz_stock}/update', [self::class, 'updateStock'])->name('update');
    });
  }

  public function getStockList(Request $request)
  {
    $this->authorize('viewAny', FzStock::class);

    return Inertia::render('FzStockManagement::ManageProductBatches',[
      'fz_stock' => FzStock::with('product_type', 'price_batch')->get(),
      'fz_stock_count' => FzStock::count(),
      'fz_oil_stock_count' => FzStock::oil()->sum('stock_quantity'),
      'fz_gallon_stock_count' => FzStock::gallon()->sum('stock_quantity'),
      'can_create_stock' => Gate::allows('create', FzStock::class),
      'can_edit_stock' => Gate::allows('update', FzStock::class),
      'stock_types' => FzProductType::all(),
      'price_batches' => FzPriceBatch::all(),
    ]);
  }

  public function createStock(CreateFzStockRequest $request)
  {
    $this->authorize('create', FzStock::class);

    $request->createStock();
    return redirect()->route('fzstock.list')->withFlash(['success' => 'Stock has been created / updated.']);
  }

  public function updateStock(CreateFzStockRequest $request, FzStock $fz_stock)
  {
    $this->authorize('update', FzStock::class);

    $request->updateStock();
    return redirect()->route('fzstock.list')->withFlash(['success' => 'Stock has been updated.']);
  }

  public function getProductBatches(Request $request)
  {
    return Inertia::render('FzStockManagement::ManageProductBatches')->withViewData([
      'title' => 'Hello theEects',
      'metaDesc' => ' This page is ...'
      ]);
    }

  }
